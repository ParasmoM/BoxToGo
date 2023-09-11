<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Users;
use App\Repository\UserRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GoogleController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('public_home');
        }

        return $clientRegistry
            ->getClient('google_main')
            ->redirect();
    }

    #[Route(path: '/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, UserRepository $userRepository)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        dd('google login', $client);
        $client = $clientRegistry->getClient('google_main');
        try {
            $googleUser = $client->fetchUser();

            // Récupérer les informations de l'utilisateur
            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $givenName = $googleUser->getGivenName();
            $familyName = $googleUser->getFamilyName();

            $userExist = $userRepository->findOneByEmail($email);
            // dd($email, $firstName, $lastName);
            if ($userExist) {
                $userExist->setGivenName($givenName)
                        ->setFamilyName($familyName);
                
                $userRepository->save($userExist, true);    
                // dd('User dans la base de donnée');
            } else {
                $new_user = new User();

                $new_user->setGivenName($givenName)
                        ->setFamilyName($familyName)
                        ->setEmail($email)
                        ->setPassword('oauth');
                        $userRepository->save($new_user, true);       
                        // dd('Nouveau dans la base de donnée');
            }

            return $this->redirectToRoute('app_home');
        } catch (\Exception $e) {
            throw new \Exception("Une erreur s'est produite lors de la création d'un nouvel utilisateur identifié par Google." . $e->getMessage(), 0, $e);

        }
    }
}