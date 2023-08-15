<?php

namespace App\Controller\Admin;

use App\Entity\SpaceCategories;
use App\Entity\SpaceEquipements;
use App\Form\SpaceCategFormType;
use App\Form\SpaceEquipmentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin/interface', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/storages', name: 'app_admin_storages')]
    public function storages(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $type = 'storages';

        $spaceCategories = new SpaceCategories();
        $formNew = $this->createForm(SpaceCategFormType::class, $spaceCategories);
        $formNew->handleRequest($request);

        $list = $entityManager->getRepository(SpaceCategories::class)->findBy([], ['name' => 'ASC']);

        if ($formNew->isSubmitted() && $formNew->isValid()) {
            $entityManager->persist($spaceCategories);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_storages', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin/_storages.html.twig', compact('formNew', 'list', 'type'));
    }

    #[Route('/admin/storages/{id}/edit', name: 'app_admin_storages_edit', methods: ['GET', 'POST'])]
    public function storageEdit(
        Request $request,
        EntityManagerInterface $entityManager,
        SpaceCategories $spaceCategories
    ): Response {
        $formEdit = $this->createForm(SpaceCategFormType::class, $spaceCategories);
        $formEdit->handleRequest($request);
        
        $itemName = $spaceCategories->getName();
        $list = $entityManager->getRepository(SpaceCategories::class)->findBy([], ['name' => 'ASC']);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_storages', [], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('edit', "");
        return $this->render('admin/admin/_storages.html.twig', compact('formEdit', 'list', 'itemName'));
    }

    #[Route('/admin/storages/{id}', name: 'app_admin_storages_delete', methods: ['POST'])]
    public function storageDelete(
        Request $request, 
        EntityManagerInterface $entityManager,
        SpaceCategories $spaceCategories
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$spaceCategories->getId(), $request->request->get('_token'))) {
            $entityManager->remove($spaceCategories);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_storages', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/equipments', name: 'app_admin_equipments')]
    public function equipments(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $type = 'equipments';

        $spaceEquipement = new SpaceEquipements();
        $formNew = $this->createForm(SpaceEquipmentFormType::class, $spaceEquipement);
        $formNew->handleRequest($request);

        $list = $entityManager->getRepository(SpaceEquipements::class)->findBy([], ['name' => 'ASC']);

        if ($formNew->isSubmitted() && $formNew->isValid()) {
            $entityManager->persist($spaceEquipement);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_equipments', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin/_equipments.html.twig', compact('formNew', 'list', 'type'));
    }

    #[Route('/admin/equipments/{id}/edit', name: 'app_admin_equipments_edit', methods: ['GET', 'POST'])]
    public function equipmentsEdit(
        Request $request,
        EntityManagerInterface $entityManager,
        SpaceEquipements $spaceEquipements
    ): Response {
        $formEdit = $this->createForm(SpaceEquipmentFormType::class, $spaceEquipements);
        $formEdit->handleRequest($request);
        
        $itemName = $spaceEquipements->getName();
        $list = $entityManager->getRepository(SpaceEquipements::class)->findBy([], ['name' => 'ASC']);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_equipments', [], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('edit', "");
        return $this->render('admin/admin/_equipments.html.twig', compact('formEdit', 'list', 'itemName'));
    }

    #[Route('/admin/equipments/{id}', name: 'app_admin_equipments_delete', methods: ['POST'])]
    public function equipmentDelete(
        Request $request, 
        EntityManagerInterface $entityManager,
        SpaceEquipements $spaceEquipements
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$spaceEquipements->getId(), $request->request->get('_token'))) {
            $entityManager->remove($spaceEquipements);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_equipments', [], Response::HTTP_SEE_OTHER);
    }
}
