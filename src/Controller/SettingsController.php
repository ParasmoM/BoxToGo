<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Adresses;
use App\Entity\UserConsent;
use App\Form\AccountFormType;
use App\Form\AdresseFormType;
use App\Form\SecurityFormType;
use App\Form\AppearanceFormType;
use App\Form\NotificationFormType;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormError;
use function PHPUnit\Framework\isEmpty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SettingsController extends AbstractController
{   
    #[Route('/settings/account/{id}', name: 'public_account')]
    public function account(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);

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

    #[Route('/settings/notifications/{id}', name: 'public_notifications')]
    public function notifications(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        $userConsents = $user->getConsent();
        $userPreferences = $user->getPreference();
        $currentPreferences = [];
        $initialData = [];

        $initialData = $this->prepareInitialData($userPreferences, $userConsents);

        $form = $this->createForm(NotificationFormType::class, $initialData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $validKeys = ['email', 'alert'];
            $notificationKeys = ['Commentaires', 'Favoris', 'Messages'];
            $newPreferences = [];
            $notifications = [];

            foreach ($form->getData() as $key => $value) {
                if ($value === true && in_array($key, $validKeys)) {
                    $newPreferences[] = $key;
                } 
                if (in_array($key, $notificationKeys)) {
                    $notifications[$key] = $value;
                }
            }
            
            if (!$userConsents->first()) {
                foreach ($notifications as $key => $value) {
                    $consent = new UserConsent();
                    $consent->setConsentType($key);
                    $consent->setConsentGiven($value);

                    $user->addConsent($consent);
                }
            } else {
                foreach ($notifications as $key => $value) {
                    foreach ($userConsents as $consent) {
                        if ($key == $consent->getConsentType() && $value != $consent->isConsentGiven()) {
                            $consent->setConsentGiven($value);
                        }
                    }
                }
            }

            if (count($currentPreferences) != count($newPreferences)) {
                $user->setPreference($newPreferences);
            }

            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/index.html.twig', compact('form'));
    }

    #[Route('/settings/preferences/{id}', name: 'public_preferences')]
    public function preferences(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {

        $form = $this->createForm(AppearanceFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appearance = $form->get('appearance')->getData();
            $language = $form->get('language')->getData();

            $user->setAppearance($appearance);
            $user->setLanguage($language);
 
            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('settings/index.html.twig', compact('form'));
    }

    #[Route('/settings/security/{id}', name: 'public_security')]
    public function security(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response {
        $form = $this->createForm(SecurityFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $currentPassword = $form->get('password')->getData(); // Assurez-vous d'avoir un champ pour le mot de passe actuel
            $newPassword = $form->get('new_password')->getData(); // Assurez-vous d'avoir un champ pour le nouveau mot de passe
            if ($userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                
                $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
    
                $userRepository->save($user);
    
                return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
            } else {
                $form->get('password')->addError(new FormError('Current password is incorrect.')); // Ajoutez une erreur si le mot de passe actuel est incorrect
            }
        }
        
        return $this->render('settings/index.html.twig', compact('form', 'user'));
    }

    #[Route('/settings/delete/{id}', name: 'public_settings_delete')]
    public function delete(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }
        
        return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/settings/payments', name: 'public_payments')]
    public function payments(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }

    private function prepareInitialData($userPreferences, $userConsents): array {
        $initialData = $userPreferences ? array_fill_keys($userPreferences, true) : [];
    
        if ($userConsents->first()) {
            foreach ($userConsents as $consent) {
                if ($consent->isConsentGiven() === true) {
                    $initialData[$consent->getConsentType()] = $consent->isConsentGiven();
                }
            }
        }
    
        return $initialData;
    }
}
