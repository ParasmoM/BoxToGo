<?php

namespace App\EventSubscriber;

use App\Repository\TalksRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class SidebarMessageSubscriber implements EventSubscriberInterface
{
    private $tokenStorage; 
    const ROUTES = ['app_talks', 'app_current_talk'];

    public function __construct(
        private TalksRepository $repository,
        TokenStorageInterface $tokenStorage,
        private Environment $twig
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_route');
        $token = $this->tokenStorage->getToken();
        
        if ($token) {
            $currentUser = $token->getUser();

            if (in_array($route, SidebarMessageSubscriber::ROUTES)) {
                // Récupération de tous les messages reçus par l'utilisateur actuel
                $receivedMessages = $this->repository->findBy(['receiver' => $currentUser]);
                // Récupération de tous les messages envoyés par l'utilisateur actuel
                $sentMessages = $this->repository->findBy(['sender' => $currentUser]);

                $conversations = $this->groupTalksByConversation($receivedMessages, $sentMessages);

                $interlocutors = $this->transformConversationsForSidebar($conversations, $currentUser->getId());
                // dd($interlocutors);
                $this->twig->addGlobal('interlocutors', $interlocutors);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * Trie et groupe les entités Talks par émetteur et date de création.
     *
     * @param array $talks Tableau d'entités Talks à trier.
     * @return array Tableau associatif de talks regroupés et triés.
     */
    public function sortAndGroupTalks(array $talks): array
    {
        // Grouper par émetteur
        $groupedBySender = $this->groupTalksBySender($talks);

        // Trier chaque groupe par createdAt
        foreach ($groupedBySender as $senderId => $senderTalks) {
            usort($senderTalks, function ($a, $b) {
                return $a->getCreatedAt() <=> $b->getCreatedAt();
            });
            $groupedBySender[$senderId] = $senderTalks;
        }

        // Trier les groupes eux-mêmes par le createdAt du dernier message dans chaque groupe
        uasort($groupedBySender, function ($a, $b) {
            $lastTalkA = end($a);
            $lastTalkB = end($b);
            return $lastTalkB->getCreatedAt() <=> $lastTalkA->getCreatedAt();
        });

        return $groupedBySender;
    }
    
    /**
     * groupTalksByConversation
     *
     * Cette fonction regroupe les messages dans des conversations en fonction des émetteurs et des destinataires.
     * 
     * @param array $receivedMessages Messages reçus par l'utilisateur actuel.
     * @param array $sentMessages Messages envoyés par l'utilisateur actuel.
     * @return array Un tableau associatif des conversations, chaque conversation contenant l'entité de l'émetteur et les messages.
     */
    public function groupTalksByConversation(array $receivedMessages, array $sentMessages): array
    {
        $groupedByConversation = [];

        // Fusionner les tableaux de messages reçus et envoyés
        $allMessages = array_merge($receivedMessages, $sentMessages);

        foreach ($allMessages as $talk) {
            $senderId = $talk->getSender()->getId();
            $receiverId = $talk->getReceiver()->getId();

            // Ordonner les IDs pour avoir une seule clé unique par paire d'IDs
            $ids = [$senderId, $receiverId];
            sort($ids);
            $conversationKey = implode('_', $ids);

            // Si la clé n'existe pas encore, créer une nouvelle entrée
            if (!isset($groupedByConversation[$conversationKey])) {
                $groupedByConversation[$conversationKey] = [
                    'sender' => $talk->getSender(), // Ajoute l'entité de l'émetteur
                    'receiver' => $talk->getReceiver(),
                    'messages' => [],  // Initialise le tableau de messages
                ];
            }

            // Ajouter le message à la conversation
            $groupedByConversation[$conversationKey]['messages'][] = $talk;
        }

        // Optionnel : trier les messages de chaque conversation par date de création
        foreach ($groupedByConversation as &$conversation) {
            usort($conversation['messages'], function ($a, $b) {
                return $a->getCreatedAt() <=> $b->getCreatedAt();
            });
        }
        // dd($groupedByConversation);
        return $groupedByConversation;
    }

    /**
     * Transforme la liste de conversations pour l'affichage dans la barre latérale.
     *
     * @param array $conversations Tableau contenant les conversations à transformer.
     * @param int $currentUserId ID de l'utilisateur actuellement connecté.
     * @return array Tableau des conversations transformées.
     */
    public function transformConversationsForSidebar(array $conversations, int $currentUserId): array
    {
        // Initialisation du tableau qui va stocker les conversations pour la barre latérale
        $sidebarConversations = [];

        // Parcours de chaque conversation pour la transformation
        foreach ($conversations as $conversation) {
            $sender = $conversation['sender'];
            $receiver = $conversation['receiver'];
            $messages = $conversation['messages'];

            // Tri des messages par date de création en ordre décroissant
            usort($messages, function ($a, $b) {
                return $b->getCreatedAt() <=> $a->getCreatedAt();
            });

            // Recherche du dernier message envoyé par l'utilisateur actuel
            $latestMessageCreatedAt = null;
            foreach ($messages as $message) {
                if ($message->getReceiver()->getId() === $currentUserId) {
                    $latestMessageCreatedAt = $message->getCreatedAt();
                    break;  // Sortir de la boucle dès que le dernier message est trouvé
                }
            }

            // Ajout du sender, du receiver et de la date de création du dernier message dans le tableau
            $sidebarConversations[] = [
                'sender' => $sender,
                'receiver' => $receiver,
                'createdAt' => $latestMessageCreatedAt ? $latestMessageCreatedAt->format('Y-m-d H:i:s') : null
            ];
        }

        return $sidebarConversations;
    }
}
