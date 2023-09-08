<?php

namespace App\Controller;

use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardUserController extends AbstractController
{
    #[Route('/dashboard/user', name: 'app_dashboard_user')]
    public function index(
        EntityManagerInterface $em
    ): Response {
        // On s'assure que l'utilisateur est connecté; sinon, on le redirige.
        if (!$this->getUser()) return $this->redirectToRoute('app_login');

        return $this->render('dashboard_user/index.html.twig', [
            'userBookings' => $em->getRepository(Reservations::class)->findBy(['user' => $this->getUser()->getId()]),
        ]);
    }
}
