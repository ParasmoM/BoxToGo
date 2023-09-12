<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Reviews;
use App\Entity\Conversations;
use App\Form\ConversationsFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\SelfMessagingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilePageController extends AbstractController
{
    #[Route('/owner/profile/{id}', name: 'profile_page')]    
    /**
     * Gère la page de profil d'un utilisateur.
     *
     * @param Users $user L'utilisateur dont le profil doit être affiché.
     * @param Request $request La requête HTTP actuelle.
     * @param EntityManagerInterface $em Le gestionnaire d'entités pour Doctrine.
     *
     * @return Response Une réponse HTTP.
     *
     * @throws UserNotAuthenticatedException Si l'utilisateur n'est pas authentifié.
     * @throws SelfMessagingException Si un utilisateur tente de s'envoyer un message à lui-même.
     */
    public function index(
        User $user,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            return $this->redirectToRoute('app_error_403');
        }

        $form = $this->creatForm($user, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie que ni le propriétaire ne peut s'envoyer de messages, ni un utilisateur non authentifié ne peut en envoyer.
            try {
                $this->validateUserAndMessaging($user, $request);
            } catch (UserNotAuthenticatedException | SelfMessagingException $e) {
                return $this->redirectToRoute('app_error_403');
            }
            
            $talks = $form->getData();
            $em->persist($talks);
            $em->flush();

            return $this->redirectToRoute('profile_page', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'reviews' => $em->getRepository(Reviews::class)->findAll(['space' => $user->getOwners()->first()]),
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Crée et manipule un formulaire pour l'entité Talks.
     *
     * @param User $user L'utilisateur avec lequel l'utilisateur actuel souhaite parler.
     * @param Request $request La requête HTTP actuelle.
     * 
     * @return Symfony\Component\Form\FormInterface Le formulaire créé.
     * 
     */
    public function creatForm(User $user,Request $request): \Symfony\Component\Form\FormInterface
    {
        $currentUser = $this->getUser();
        $talks = null;

        if (!$currentUser == null) {
            $talks = new Conversations();
            $talks->setSentByUser($currentUser);
            $talks->setReceivedByUser($user);
        }

        $form = $this->createForm(ConversationsFormType::class, $talks);
        $form->handleRequest($request);
        
        return $form;
    }

    private function validateUserAndMessaging(User $user, Request $request): void
    {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être authentifié pour créer un formulaire de discussion.");
            }
            
            if ($this->getUser() === $user) {
                throw new SelfMessagingException('Envoi de message à soi-même non autorisé.');
            }
        } catch (UserNotAuthenticatedException | SelfMessagingException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());

            throw $e; 
        }
    }
}
