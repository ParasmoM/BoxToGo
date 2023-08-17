<?php

namespace App\Controller;

use App\Entity\Spaces;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyDetailsController extends AbstractController
{
    #[Route('/property/details/{id}', name: 'app_property_details')]
    public function index(
        Spaces $space
    ): Response {
        // dd($space->getEquipment());
        return $this->render('property_details/index.html.twig', compact('space'));
    }
}
