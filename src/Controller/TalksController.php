<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Conversations;
use App\Form\ConversationsFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\SelfMessagingException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TalksController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        $this->em = $em;
    }

    #[Route('/talks', name: 'app_talks')]
    public function index(Request $request): Response
    {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page de messages.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());

            return $this->redirectToRoute('app_error_403');
        }

        $currentInterlocutorId = $this->redirectToLatestConversation();

        if ($currentInterlocutorId) {
            return $this->redirectToRoute('app_current_talk', ['id' => $currentInterlocutorId], Response::HTTP_SEE_OTHER);
        } 

        return $this->render('talks/index.html.twig', []);        
    }
    
    #[Route('/talks/{id}', name: 'app_current_talk')]
    public function currentTalk(User $currentInterlocutor, Request $request)
    {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page de messages.");
            }
            
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());

            return $this->redirectToRoute('app_error_403');
        }
        
        try {
            $conversations = $this->getConversationsWithInterlocutor($currentInterlocutor);

            if (empty($conversations['conversations'])) {
                throw new Exception("Aucune conversation trouvée entre les deux utilisateurs.");
            }
        } catch (\Exception $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            
            return $this->redirectToRoute('app_error_404');
        }
        
        $form = $this->createTalksForm($request, $currentInterlocutor);

        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie que ni le propriétaire ne peut s'envoyer de messages, ni un utilisateur non authentifié ne peut en envoyer.
            try {
                $this->validateUserAndMessaging($form->getData()->getReceivedByUser(), $request);
            } catch (UserNotAuthenticatedException | SelfMessagingException $e) {
                return $this->redirectToRoute('app_error_403');
            }

            $talks = $form->getData();
            // dd($form->getData()->getReceivedByUser());
            $this->em->persist($talks);
            $this->em->flush();

            return $this->redirectToRoute('app_talks', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('talks/_content.html.twig', [
            'currentInterlocutor' => $currentInterlocutor,
            'conversations' => $conversations,
            'form' => $form->createView(),
        ]);
    }

    /**
     * redirectToLatestConversation
     *
     * Cette méthode redirige vers la dernière conversation en cours pour l'utilisateur connecté.
     *
     * @param EntityManagerInterface $em Le gestionnaire d'entités pour la communication avec la base de données.
     * @return int|null Retourne soit l'ID de l'interlocuteur dans la dernière conversation, soit null si aucune conversation n'est trouvée.
     */
    public function redirectToLatestConversation(): ?int
    {
        // Récupérer l'utilisateur actuellement connecté et son ID
        $currentUser = $this->getUser();
        $currentUserId = $currentUser->getId();
    
        // Essayer de trouver le dernier message où l'utilisateur actuel est soit le destinataire soit l'émetteur
        $latestMessage = $this->em->getRepository(Conversations::class)->findOneBy(
            ['receivedByUser' => $currentUserId],
            ['id' => 'DESC']
        ) ?? $this->em->getRepository(Conversations::class)->findOneBy(
            ['sentByUser' => $currentUserId],
            ['id' => 'DESC']
        );
    
        // Vérifier si un dernier message a été trouvé
        if ($latestMessage) {
            // Déterminer qui est l'interlocuteur dans cette conversation
            $currentInterlocutor = ($currentUser === $latestMessage->getSentByUser()) ? $latestMessage->getReceivedByUser() : $latestMessage->getSentByUser();
    
            // Si un interlocuteur est trouvé, récupérer son ID
            if ($currentInterlocutor) {
                $currentInterlocutorId = $currentInterlocutor->getId();
            }
            // Retourner l'ID de l'interlocuteur s'il est trouvé, sinon retourner null
            return $currentInterlocutorId;
        }

        return null;
    }

    /**
     * getConversationsWithInterlocutor
     *
     * Cette méthode récupère toutes les conversations entre l'utilisateur actuellement connecté
     * et un interlocuteur donné. Elle renvoie ensuite un tableau associatif contenant l'interlocuteur
     * et les messages triés par date de création.
     *
     * @param  object $currentInterlocutor L'entité représentant l'interlocuteur actuel dans la conversation.
     * @return array                       Un tableau associatif contenant deux clés : 'interlocutor' et 'conversations'.
     *                                     - 'interlocutor' contient l'entité de l'interlocuteur.
     *                                     - 'conversations' contient un tableau des messages triés par date de création.
     */
    public function getConversationsWithInterlocutor($currentInterlocutor): array {
        // Chercher tous les messages envoyés par l'interlocuteur actuel à l'utilisateur connecté
        $messagesReceiver = $this->em->getRepository(Conversations::class)
            ->findBy(['sentByUser' => $currentInterlocutor, 'receivedByUser' => $this->getUser()]);
    
        // Chercher tous les messages envoyés par l'utilisateur connecté à l'interlocuteur actuel
        $messagesSender = $this->em->getRepository(Conversations::class)
            ->findBy(['sentByUser' => $this->getUser(), 'receivedByUser' => $currentInterlocutor]);

        // Fusionner les deux tableaux de messages
        $mergedMessages = array_merge($messagesReceiver, $messagesSender);
    
        // Trier les messages par 'createdAt'
        usort($mergedMessages, function ($a, $b) {
            return $a->getCreateAt() <=> $b->getCreateAt();
        });
    
        // Déterminer l'interlocuteur (doit être différent de l'utilisateur connecté)
        $interlocutor = ($currentInterlocutor !== $this->getUser()) ? $currentInterlocutor : null;
    
        // Retourner le résultat sous forme de tableau associatif
        return [
            'interlocutor' => $interlocutor,
            'conversations' => $mergedMessages,
        ];
    }

    /**
     * createTalksForm
     *
     * Cette méthode crée un formulaire pour l'entité "Talks" en définissant l'émetteur (sender) 
     * et le destinataire (receiver) de la conversation.
     *
     * @param  Request $request Le Request actuel pour gérer la soumission du formulaire.
     * @param  object $currentInterlocutor L'entité représentant l'interlocuteur actuel dans la conversation.
     * @return FormInterface Le formulaire créé.
     */
    public function createTalksForm(Request $request, $currentInterlocutor): FormInterface {
        // Initialisation d'une nouvelle entité "Talks"
        $talks = new Conversations();
        
        // Définir l'émetteur et le destinataire de la conversation
        $talks->setSentByUser($this->getUser());
        $talks->setReceivedByUser($currentInterlocutor);

        // Création du formulaire en utilisant TalksFormType
        $form = $this->createForm(ConversationsFormType::class, $talks);

        // Traitement de la soumission du formulaire si elle existe
        $form->handleRequest($request);

        // Retourner le formulaire créé
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
