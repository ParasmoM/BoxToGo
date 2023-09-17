<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Form\ResaFormType;
use App\Entity\Reservations;
use App\Model\SearchBarHome;
use App\Form\SearchBarHomeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyDetailsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/property/details/{id}', name: 'public_property_details')]
    public function index(
        Spaces $space,
        Request $request,
    ): Response {
        $currentReservation = $this->findCurrentReservationAndUpdateStatus($space);
        // dd($currentReservation, $space);
        if ($currentReservation) {
            // On renvoie la réservation avec le statut 'busy'.
            $resa = $currentReservation;
            // dd(1, $resa);
        } else {
            $resa = new Reservations($space);
        }
        // dd($resa);
        
        $form = $this->createForm(ResaFormType::class, $resa);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On s'assure que l'utilisateur est connecté ; sinon, on le redirige.
            if (!$this->getUser()) return $this->redirectToRoute('app_login');

            // On vérifie le statut du bien ; s'il est 'occupé', nous effectuons une redirection.
            if ($space->getStatus() == 'busy') return $this->redirectToRoute('public_home');

            // Nous nous assurons qu'un hôte ne peut pas réserver son propre bien.
            if ($this->getUser()->getId() == $space->getOwnedByUser()->getId()) return $this->redirectToRoute('public_home');

            // Nous associons la personne qui loue au bien et nous changeons le statut de ce dernier.
            // $space->setRentedByUser($this->getUser());// ! Faut pas faire ça maintenant 
            // $space->setStatus('in processing');// ! Faut pas faire ça maintenant 
            // $this->getUser()->addRenter($space); // ! Faut pas faire ça maintenant 

            $resa->setUser($this->getUser());
            $resa->setStatus('in processing');
            $this->em->persist($resa);
            $this->em->flush();
            return $this->redirectToRoute('app_checkout', ['id' => $resa->getId()]);
        }

        $searchBar = new SearchBarHome();

        $formSearch = $this->createForm(SearchBarHomeFormType::class, $searchBar);
        $formSearch->handleRequest($request);
        
        return $this->render('property_details/index.html.twig', [
            'form' => $form->createView(),
            'formSearch' => $formSearch,
            'space' => $space,
            'imageCount' => count($this->em->getRepository(Images::class)->findBy(['spaces' => $space])),
            'reviews' => $this->em->getRepository(Reviews::class)->findBy(['spaces' => $space]),
        ]);
    }

    public function findCurrentReservationAndUpdateStatus(Spaces $space)
    {
        $currentReservation = false; 

        // Nous vérifions si ce bien est présent dans la base de données.
        $reservations = $this->em->getRepository(Reservations::class)->findBy(['space' => $space->getId()]);
        // dd($reservations, $space);

        foreach ($reservations as $reservation) 
        {
            // Nous mettons à jour le statut de la réservation si cela est nécessaire.
            $reservation->updateStatusBasedOnDate();
            $this->em->persist($reservation);
            
            // Si la réservation est toujours en cours, nous ajustons le statut du bien en conséquence.
            if ($reservation->getStatus() === 'busy') {

                // dd($reservation, $space);
                $currentReservation = $reservation;
                $space->setStatus('busy');
                
            } 

            if ($reservation->getStatus() === 'in processing' && $reservation->getPayment() === null) {
                $reservation->setStatus('failed');
            }
        }

        if (!$currentReservation) {
            
            if ($space->getRentedByUser()) {
                $space->getRentedByUser()->removeRenter($space);
            }
            $space->setRentedByUser(null);
            $space->setStatus('free');
        }

        $this->em->flush();
        // Renvoie la réservation en cours, ou retourne 'false' si aucune n'est trouvée.
        return $currentReservation ?: false; 
    }

}
