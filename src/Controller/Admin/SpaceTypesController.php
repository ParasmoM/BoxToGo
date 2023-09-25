<?php

namespace App\Controller\Admin;

use App\Entity\SpaceTypes;
use App\Form\SpaceTypesType;
use App\Repository\SpaceTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/space/types')]
class SpaceTypesController extends AbstractController
{
    #[Route('/{id}', name: 'app_space_types_delete', methods: ['POST'])]
    public function delete(Request $request, SpaceTypes $spaceType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$spaceType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($spaceType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}
