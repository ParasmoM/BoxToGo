<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\SpacesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OwnerReservationsController extends AbstractController
{
    #[Route('/owner/{id}/reservations', name: 'priver_owner_reservations')]
    public function index(
        Request $request,
        Users $user,
        SpacesRepository $spacesRepository
    ): Response {
        $spaces = $spacesRepository->findBy(['user' => $user->getId()], ['user' => 'ASC']);
        
        return $this->render('owner_reservations/index.html.twig', compact('spaces'));
    }
}
