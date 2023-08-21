<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Spaces;
use App\Form\CombinedFormType;
use App\DTO\DynamicCompositeModel;
use App\DTO\NewItemCompositeModel;
use App\Repository\SpacesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OwnerReservationsController extends AbstractController
{
    #[Route('/owner/reservations/{id}', name: 'priver_owner_reservations')]
    public function index(
        Request $request,
        Users $user,
        SpacesRepository $spacesRepository
    ): Response {
        $spaces = $spacesRepository->findBy(['user' => $user->getId()], ['user' => 'ASC']);
        
        return $this->render('owner_reservations/index.html.twig', compact('spaces'));
    }

    #[Route('/owner/edit/{id}', name: 'priver_owner_edit')]
    public function edit(
        Request $request,
        Spaces $space,
        SpacesRepository $spacesRepository
    ): Response {
        $model = new DynamicCompositeModel();

        $model->hydrate($space);
        
        $form = $this->createForm(CombinedFormType::class, $model);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $spacesRepository->save($space);
            return $this->redirectToRoute('priver_owner_reservations', ['id' => $space->getUser()->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('owner_reservations/form.html.twig', compact('space', 'form'));
    }
}
