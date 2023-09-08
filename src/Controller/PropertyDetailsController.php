<?php

namespace App\Controller;

use App\Entity\Spaces;
use App\Form\ResaFormType;
use App\Entity\SpaceImages;
use App\Entity\Reservations;
use App\Entity\Reviews;
use Doctrine\ORM\EntityManager;
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
        
        if ($currentReservation) {
            // On renvoie la réservation avec le statut 'busy'.
            $resa = $currentReservation;
            // dd(1, $resa);
        } else {
            $resa = new Reservations($space);
            $resa->setPrice($space->getPrice());
            $resa->setSpace($space);
            // dd(2, $resa);
        }

        $reviews = $this->em->getRepository(Reviews::class)->findBy(['space' => $space]);
        // dd($reviews);
        // dd($space->getHost()->getId(), $this->getUser()->getId());
        $nbr = count($this->em->getRepository(SpaceImages::class)->findBy(['space' => $space])) - 1;

        $form = $this->createForm(ResaFormType::class, $resa);
        $form->handleRequest($request);
        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            // On s'assure que l'utilisateur est connecté ; sinon, on le redirige.
            if (!$this->getUser()) return $this->redirectToRoute('app_login');

            // On vérifie le statut du bien ; s'il est 'occupé', nous effectuons une redirection.
            if ($space->getStatus() == 'busy') return $this->redirectToRoute('public_home');

            // Nous nous assurons qu'un hôte ne peut pas réserver son propre bien.
            if ($this->getUser()->getId() == $space->getHost()->getId()) return $this->redirectToRoute('public_home');

            // Nous associons la personne qui loue au bien et nous changeons le statut de ce dernier.
            $space->setUser($this->getUser());
            $space->setStatus('busy');
            $this->getUser()->addRenter($space);

            $resa->setUser($this->getUser());
            $this->em->persist($resa);
            $this->em->flush();
            return $this->redirectToRoute('app_checkout', ['id' => $resa->getId()]);
        }
        return $this->render('property_details/index.html.twig', compact('space', 'nbr', 'form', 'reviews'));
    }

    public function findCurrentReservationAndUpdateStatus(Spaces $space)
    {
        // Nous vérifions si ce bien est présent dans la base de données.
        $reservations = $this->em->getRepository(Reservations::class)->findBy(['space' => $space->getId()]);

        // if (empty($reservations)) {

        //     dd($space);
        // }
        // dd($reservations);
        // Nous changeons le statut du bien à 'libre' avant de procéder à la vérification.
        $currentReservation = false; 
        $space->setStatus('free');

        foreach ($reservations as $reservation) 
        {
            // Nous mettons à jour le statut de la réservation si cela est nécessaire.
            $reservation->updateStatusBasedOnDate();
            $this->em->persist($reservation);

            // Si la réservation est toujours en cours, nous ajustons le statut du bien en conséquence.
            if ($reservation->getStatus() === 'busy') {
                $currentReservation = $reservation;
                $space->setStatus('busy');
            }
            if ($reservation->getStatus() === 'finished') {
                if (!empty($reservation->getUser())) {
                    $reservation->getUser()->removeRenter($space);
                }
                // dd($reservation->getUser(), $reservation->getSpace(), $reservation);
            }
        }

        $this->em->flush();

        // Renvoie la réservation en cours, ou retourne 'false' si aucune n'est trouvée.
        return $currentReservation ?: false; 
    }

}
