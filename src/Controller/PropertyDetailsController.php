<?php

namespace App\Controller;

use App\Entity\Spaces;
use App\Entity\SpaceImages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyDetailsController extends AbstractController
{
    #[Route('/property/details/{id}', name: 'public_property_details')]
    public function index(
        Spaces $space,
        EntityManagerInterface $entityManager,
    ): Response {
        $nbr = count($entityManager->getRepository(SpaceImages::class)->findBy(['space' => $space])) - 1;
        // dd($nbr);
        return $this->render('property_details/index.html.twig', compact('space', 'nbr'));
    }
}
