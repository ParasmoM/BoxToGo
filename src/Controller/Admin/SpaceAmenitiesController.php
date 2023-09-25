<?php

namespace App\Controller\Admin;

use App\Entity\SpaceAmenities;
use App\Form\SpaceAmenitiesType;
use App\Repository\SpaceAmenitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/space/amenities')]
class SpaceAmenitiesController extends AbstractController
{
    #[Route('/{id}', name: 'app_space_amenities_delete', methods: ['POST'])]
    public function delete(Request $request, SpaceAmenities $spaceAmenity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$spaceAmenity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($spaceAmenity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}
