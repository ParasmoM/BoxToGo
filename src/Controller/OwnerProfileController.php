<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Spaces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OwnerProfileController extends AbstractController
{
    #[Route('/owner/profile/{id}', name: 'public_owner_profile')]
    public function index(
        Users $host,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        // $spaces = $entityManager->getRepository(Spaces::class)->findBy(['host' => $host]);
        // dd($spaces);
        return $this->render('owner_profile/index.html.twig', compact('host'));
    }
}
