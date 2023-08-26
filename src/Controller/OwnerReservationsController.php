<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Spaces;
use App\Form\CombinedFormType;
use App\DTO\DynamicCompositeModel;
use App\DTO\NewItemCompositeModel;
use App\Repository\SpacesRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OwnerReservationsController extends AbstractController
{
    #[Route('/owner/annonces', name: 'owner_annonces')]
    public function annonce(
        Request $request,
    ): Response {
        return $this->render('owner_reservations/annonces.html.twig');
    }

    #[Route('/owner/reservations', name: 'owner_reservations')]
    public function reservation(
        Request $request,
    ): Response {
        return $this->render('owner_reservations/index.html.twig');
    }

    #[Route('/owner/edit/{id}', name: 'owner_edit')]
    public function edit(
        Request $request,
        Spaces $space,
        SpacesRepository $spacesRepository,
        UsersRepository $usersRepository
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$space->getHost() == $this->getUser()) return $this->redirectToRoute('public_home');

        $user = $space->getHost();
        $model = new DynamicCompositeModel();
        $model->hydrate($space);
        
        $form = $this->createForm(CombinedFormType::class, $model);
        // Si vous voulez définir la valeur du champ status basée sur une propriété du $user
        $statusFromUser = $user->getStatus();
        if ($statusFromUser) {
            $form->get('equipment')->get('status')->setData($statusFromUser);
        } else {
            $form->get('equipment')->get('status')->setData('Particulier');
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status = $request->request->all()['combined_form']['equipment']['status'];
            
            $user->setStatus($status);
            $usersRepository->save($user);
            $spacesRepository->save($space);
            return $this->redirectToRoute('owner_annonces', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('owner_reservations/edit.html.twig', compact('space', 'form'));
    }
}
