<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Adresses;
use App\Entity\SpaceImages;
use App\Entity\UserConsent;
use App\Form\AccountFormType;
use App\Form\AdresseFormType;
use App\Form\SecurityFormType;
use App\Form\AppearanceFormType;
use App\Services\PictureServices;
use App\Form\NotificationFormType;
use App\Repository\UsersRepository;
use App\DTO\FormSettingsAccountModel;
use Symfony\Component\Form\FormError;
use function PHPUnit\Framework\isEmpty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Form\Combined\FormSettingsAccountType;
use App\Repository\SpaceImagesRepository;
use App\Repository\SpacesRepository;
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
        PictureServices $pictureService,
        EntityManagerInterface $entityManager,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$this->getUser()->getId() == $user->getId()) return $this->redirectToRoute('public_home');
        // dd($user);
        $DTO_MODEL = new FormSettingsAccountModel();
        $DTO_MODEL->setAccount($user);
        if ($user->getContent()) {
            // dd($user->getContent());
            $DTO_MODEL->setDescription($user->getContent());
        } else {
            $DTO_MODEL->getDescription()->setUser($user);
        }
        // dd( $DTO_MODEL->getDescription());
        $form = $this->createForm(FormSettingsAccountType::class, $DTO_MODEL);
        $form->get('description')->remove('titleFr');
        $form->get('description')->remove('titleEn');
        $form->get('description')->remove('titleNl');
        $form->handleRequest($request);

        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('account')->getData();
            $photo = $form->get('photo')->getData();

            $imagePath = $form->all()['photo']->get('imagePath')->getData();
            $folder = 'Archives/Photos/' . $user->getId() . '/Profile';

            if ($photo->getImagePath()) {
                $pictureService->deleteImage($folder);
                $fichier = $pictureService->add($imagePath, $folder);
                $photo->setUser($user);
                $photo->setImagePath($fichier);
                $photo->setSortOrder(21);
                $user->setProfilePicture($fichier);
            }
            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/_account.html.twig', compact('form'));
    }

    #[Route('/photo/delete/{id}', name: 'public_photo_delete', methods: ['POST'])]
    public function deleteAccount(
        Request $request,
        SpaceImages $image,
        SpaceImagesRepository $spaceImagesRepository,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $spaceImagesRepository->remove($image);
        }
        
        return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/settings/address/{id}', name: 'public_address')]
    public function address(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$this->getUser()->getId() == $user->getId()) return $this->redirectToRoute('public_home');

        if ($user->getAdresse()) {
            $adresse = $user->getAdresse();
        } else {
            $adresse = new Adresses();
        }

        $form = $this->createForm(AdresseFormType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setCountry('Belgique');
            $user->setAdresse($adresse);
            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/_address.html.twig', compact('form'));
    }

    #[Route('/settings/notifications/{id}', name: 'public_notifications')]
    public function notifications(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$this->getUser()->getId() == $user->getId()) return $this->redirectToRoute('public_home');

        $data = $this->prepareInitialData($user);
        $initialData = $data['initialData'];
        $userConsents = $data['userConsents'];
        $userPreferences = $data['userPreferences'];
        
        $form = $this->createForm(NotificationFormType::class, $initialData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPreferences = $this->extractPreferences($form->getData());
            $notifications = $this->extractNotifications($form->getData());
        
            $this->updateConsents($user, $notifications, $userConsents);
        
            if (count($userPreferences) != count($newPreferences)) {
                $user->setPreference($newPreferences);
            }
        
            $userRepository->save($user);

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/_notifications.html.twig', compact('form'));
    }

    #[Route('/settings/preferences/{id}', name: 'public_preferences')]
    public function preferences(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$this->getUser()->getId() == $user->getId()) return $this->redirectToRoute('public_home');

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
        return $this->render('settings/_preferences.html.twig', compact('form'));
    }

    #[Route('/settings/security/{id}', name: 'public_security')]
    public function security(
        Request $request,
        Users $user,
        UsersRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$this->getUser()->getId() == $user->getId()) return $this->redirectToRoute('public_home');

        $form = $this->createForm(SecurityFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $currentPassword = $form->get('password')->getData(); 
            $newPassword = $form->get('new_password')->getData(); 
            if ($userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                
                $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
    
                $userRepository->save($user);
    
                return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
            } else {
                $form->get('password')->addError(new FormError('Current password is incorrect.')); 
            }
        }
        
        return $this->render('settings/_security.html.twig', compact('form', 'user'));
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

    private function prepareInitialData($user): array {
        $userConsents = $user->getConsent();
        $userPreferences = $user->getPreference();
        
        $initialData = $userPreferences ? array_fill_keys($userPreferences, true) : [];
        
        if ($userConsents->first()) {
            foreach ($userConsents as $consent) {
                if ($consent->isConsentGiven() === true) {
                    $initialData[$consent->getConsentType()] = $consent->isConsentGiven();
                }
            }
        }
        
        return [
            'initialData' => $initialData,
            'userConsents' => $userConsents,
            'userPreferences' => $userPreferences
        ];
    }

    private function extractPreferences($formData) {
        $validKeys = ['email', 'alert'];
        $preferences = [];
        foreach ($formData as $key => $value) {
            if ($value === true && in_array($key, $validKeys)) {
                $preferences[] = $key;
            }
        }
        return $preferences;
    }
    
    private function extractNotifications($formData) {
        $notificationKeys = ['Commentaires', 'Favoris', 'Messages'];
        $notifications = [];
        foreach ($formData as $key => $value) {
            if (in_array($key, $notificationKeys)) {
                $notifications[$key] = $value;
            }
        }
        return $notifications;
    }
    
    private function updateConsents($user, $notifications, $userConsents) {
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
    }
}
