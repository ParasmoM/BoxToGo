<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OwnerProfileController extends AbstractController
{
    #[Route('/owner/profile/{id}', name: 'public_owner_profile')]
    public function index(
        Users $user
    ): Response {
        return $this->render('owner_profile/index.html.twig', compact('user'));
    }
}
