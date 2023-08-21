<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserReservationsController extends AbstractController
{
    #[Route('/user/{id}/reservations', name: 'public_user_reservations')]
    public function index(): Response
    {
        return $this->render('user_reservations/index.html.twig', [
            'controller_name' => 'UserReservationsController',
        ]);
    }
}
