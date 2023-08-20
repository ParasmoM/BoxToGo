<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Adresses;
use App\Form\AccountFormType;
use App\Form\AdresseFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    #[Route('/settings/account/{id}', name: 'public_account')]
    public function account(
        Request $request,
        Users $user,
    ): Response {
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/index.html.twig', compact('form'));
    }

    #[Route('/settings/address/{id}', name: 'public_address')]
    public function address(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        if ($user->getAdresse()) {
            $adresse = $user->getAdresse();
        } else {
            $adresse = new Adresses();
        }

        $form = $this->createForm(AdresseFormType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAdresse($adresse);
            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/index.html.twig', compact('form'));
    }

    #[Route('/settings/notifications', name: 'public_notifications')]
    public function notifications(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }

    #[Route('/settings/preferences', name: 'public_preferences')]
    public function preferences(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }

    #[Route('/settings/security', name: 'public_security')]
    public function security(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }

    #[Route('/settings/payments', name: 'public_payments')]
    public function payments(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
}
