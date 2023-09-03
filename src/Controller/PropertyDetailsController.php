<?php

namespace App\Controller;

use App\Entity\Spaces;
use App\Form\ResaFormType;
use App\Entity\SpaceImages;
use App\Entity\Reservations;
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
        EntityManagerInterface $entityManager,
    ): Response {
        $currentReservation = $this->findCurrentReservationAndUpdateStatus($space);
        // dd($currentReservation);
        if ($currentReservation) {
            $resa = $currentReservation;
            // dd(1, $resa);
        } else {
            $resa = new Reservations($space);
            $resa->setPrice($space->getPrice());
            $resa->setSpace($space);
            // dd(2, $resa);
        }

        // dd($space->getHost()->getId(), $this->getUser()->getId());
        $nbr = count($entityManager->getRepository(SpaceImages::class)->findBy(['space' => $space])) - 1;

        $form = $this->createForm(ResaFormType::class, $resa);
        $form->handleRequest($request);
        // dd($resa);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) return $this->redirectToRoute('app_login');
            if ($space->getStatus() == 'busy') return $this->redirectToRoute('public_home');
            if ($this->getUser()->getId() == $space->getHost()->getId()) return $this->redirectToRoute('public_home');

            $space->setStatus('busy');
            $resa->setUser($this->getUser());
            $entityManager->persist($resa);
            $entityManager->flush();
            return $this->redirectToRoute('app_checkout', ['id' => $resa->getId()]);
        }
        return $this->render('property_details/index.html.twig', compact('space', 'nbr', 'form'));
    }

    public function findCurrentReservationAndUpdateStatus(Spaces $space)
    {
        $reservations = $this->em->getRepository(Reservations::class)->findBy(['space' => $space->getId()]);
        $currentReservation = false; // Initialisation à false
        $space->setStatus('free');

        foreach ($reservations as $reservation) 
        {
            $reservation->updateStatusBasedOnDate();
            $this->em->persist($reservation);

            if ($reservation->getStatus() === 'busy') {
                $currentReservation = $reservation;
                $space->setStatus('busy');
            }
        }

        $this->em->flush();

        return $currentReservation ?: false; // Retourne la réservation courante ou false si aucune n'est trouvée
    }

}
