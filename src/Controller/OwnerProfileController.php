<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OwnerProfileController extends AbstractController
{
    #[Route('/owner/profile', name: 'public_owner_profile')]
    public function index(
        Users $host
    ): Response {
        return $this->render('owner_profile/index.html.twig', compact('host'));
    }
}
