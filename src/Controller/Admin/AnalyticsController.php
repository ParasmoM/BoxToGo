<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\User;
use App\Entity\SpaceTypes;
use App\Entity\SpaceAmenities;
use App\Model\SearchDataAdmin;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Admin\SearchDataAdminFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnalyticsController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
        private TranslatorInterface $trans,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->trans = $trans;
    }

    #[Route('/admin', name: 'admin_analytics', methods: ['GET', 'POST'])]
    public function analytics(Request $request, SessionInterface $session): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $flashMessage = null;
        // Récupération des messages flash "success"
        $messagesSuccess = $session->getFlashBag()->get('success', []);

        // Récupération des messages flash "error"
        $messagesError = $session->getFlashBag()->get('error', []);

        if ($messagesSuccess) {
            $flashMessage['success'] = $messagesSuccess[0];
        }
        if ($messagesError) {
            $flashMessage['error'] = $messagesError[0];
        }

        // Récupère les données de différents dépôts
        $entityData = $this->fetchEntities();
        
        // Récupère les données de différents dépôts pour les stastitiques cards
        $datas = $this->collectEntityData();
        
        // Crée un formulaire qui gère le searchBar
        $categoriesArray = [];
        foreach ($entityData['categories'] as $categ) {
            $categoriesArray[$categ->getName($request->getLocale())] = $categ->getId(); 
        }

        $searchBar = new SearchDataAdmin();
        $formSearchBar = $this->createForm(SearchDataAdminFormType::class, $searchBar, [
            'categories' => $categoriesArray
        ]);
        $formSearchBar->handleRequest($request);
        if ($formSearchBar->isSubmitted() && $formSearchBar->isValid()) {            
            $data = [];
            // Si seulement Category est de définis retourne des instances des SPACES
            if (isset($searchBar->category) && !empty($searchBar->category) && !isset($searchBar->status)) {
                
                return $this->redirectToRoute('app_admin_notices', ['id' => $searchBar->category]);

                // Si seulement Status est de definis retourne des instances des SPACES et USER
            } else if (isset($searchBar->status) && !empty($searchBar->status) && !isset($searchBar->category)) {

                return $this->redirectToRoute('app_admin_users', ['status' => $searchBar->status]);
                dd($searchBar->status);

                // Si le deux sont definis retourne des instances des SPACES
            } else if (isset($searchBar->status) && !empty($searchBar->status) && !isset($searchBar->category)) {
                dd(2);
            } else if (isset($searchBar->customer)) {
                return $this->redirectToRoute('app_admin_users', ['name' => $searchBar->customer]);
            }
            else if (isset($searchBar->reference)) {
                $firstTwoCharacters = strtoupper(substr($searchBar->reference, 0, 2));

                if ($firstTwoCharacters === 'S-') {
                    return $this->redirectToRoute('app_admin_notices', ['ref' => $searchBar->reference]);
                }
                if ($firstTwoCharacters === 'R-') {
                    return $this->redirectToRoute('app_admin_resa', ['ref' => $searchBar->reference]);
                }
            }
        }
        
        // Crée un formulaire destiné à l'ajout d'un nouvel administrateur
        $formNewAdmin = $this->createdForm('NewAdmin', $request);
        if ($formNewAdmin->isSubmitted() && $formNewAdmin->isValid()) {
            $newAdmin = $this->em->getRepository(User::class)->findOneBy(['email' => $formNewAdmin->getData()['email']]);

            if ($newAdmin === null) {
                $flashMessage = $this->trans->trans('This user is not in the database.');

                $this->addFlash('error', $flashMessage);

                return $this->redirectToRoute('admin_analytics');  
            }
    
            $arrayRoles = $newAdmin->getRoles();

            if (!in_array('ROLE_ADMIN', $arrayRoles)) {
                array_push($arrayRoles, 'ROLE_ADMIN');

                $newAdmin->setRoles($arrayRoles);

                $this->em->getRepository(User::class)->save($newAdmin);

                $flashMessage = $this->trans->trans('Congratulations, you have added a new administrator.');

                $this->addFlash('success', $flashMessage);
            }
                        
            return $this->redirectToRoute('admin_analytics');
        }

        // Crée un formulaire destiné à l'ajout d'un nouveau type d'espace
        $formSpaceType = $this->createdForm('SpaceTypes', $request);
        if ($formSpaceType->isSubmitted() && $formSpaceType->isValid()) {
            $newSpaceType = $formSpaceType->getData();

            $this->em->persist($newSpaceType);
            $this->em->flush();
            
            $flashMessage = $this->trans->trans('Congratulations, you have added a new type of space.');
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('admin_analytics');
        }

        // Crée un formulaire destiné à l'ajout de nouveaux équipements pour un espace
        $formAmenities = $this->createdForm('SpaceAmenities', $request);
        if ($formAmenities->isSubmitted() && $formAmenities->isValid()) {
            $newAmenity = $formAmenities->getData();

            $this->em->persist($newAmenity);
            $this->em->flush();
            
            $flashMessage = $this->trans->trans('Congratulations, you have added a new piece of equipment for a space.');
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('admin_analytics');
        }
        
        return $this->render('admin/analytics/analytics.html.twig', [
            'datas' => $datas,
            'entityData' => $entityData,
            'formSearchBar' => $formSearchBar,
            'formNewAdmin' => $formNewAdmin,
            'formSpaceType' => $formSpaceType,
            'formAmenities' => $formAmenities,
            'flashMessage' => $flashMessage,
        ]);
    }

    /**
     * fetchEntities
     *
     * Cette méthode récupère des entités à partir de différents dépôts.
     *
     * @return array Un tableau associatif contenant les différentes entités récupérées.
     */
    public function fetchEntities()
    {
        // Initialiser un tableau pour stocker les entités récupérées
        $result = [];

        // Récupérer et stocker les types d'espaces
        $repoType = $this->em->getRepository(SpaceTypes::class);
        $result['categories'] = $repoType->findBy([]);

        // Récupérer et stocker les utilisateurs
        $repoUser = $this->em->getRepository(User::class);
        $users = $repoUser->findBy([]);

        // Filtrer les utilisateurs pour ne garder que ceux ayant le rôle 'ROLE_ADMIN'
        $filteredUsersAdmin = array_filter($users, function ($user) {
            return in_array('ROLE_ADMIN', $user->getRoles());
        });

        // Stocker les utilisateurs filtrés dans le tableau de résultat
        $result['users'] = $filteredUsersAdmin;

        // Récupérer et stocker les équipements d'espaces
        $repoAmenity = $this->em->getRepository(SpaceAmenities::class);
        $result['amenities'] = $repoAmenity->findBy([]);

        return $result;
    }

    /**
     * collectEntityData
     *
     * Cette méthode récupère des informations sur différentes entités.
     * Elle renvoie des données récentes et des comptes pour chaque type d'entité.
     *
     * @return array Un tableau contenant les données collectées.
     */
    public function collectEntityData()
    {
        // Définition des entités à interroger et des noms associés
        $entities = [
            'App\Entity\User' => 'users',
            'App\Entity\Spaces' => 'announcements',
            'App\Entity\Reservations' => 'reservations',
            'App\Entity\Reviews' => 'comments',
        ];

        // Initialisation du tableau pour stocker les données collectées
        $collectedData = [];

        // Parcours de chaque entité pour récupérer les informations
        foreach ($entities as $entityClass => $name) {
            $repo = $this->em->getRepository($entityClass);
            $instances = $repo->findBy([], ['createAt' => 'DESC'], 5);
            $count = count($repo->findBy([], ['createAt' => 'DESC']));

            $data = [
                'name' => $name,
                'instances' => $instances,
                'nbr' => $count,
            ];

            // Compte des nouvelles instances, si la méthode existe
            if (method_exists($repo, 'countByPeriod')) {
                $data['new'] = $repo->countByPeriod();
            }

            // Calcul de la note moyenne pour les commentaires, si la méthode existe
            if ($entityClass === 'App\Entity\Reviews') {
                if (method_exists($repo, 'calculateAverageRating')) {
                    $data['ratings'] = $repo->calculateAverageRating();
                }
            }

            $collectedData[] = $data;
        }

        // Calcul de la note moyenne globale pour les commentaires
        $collectedData[] = [
            'name' => 'ratings',
            'nbr' => $this->em->getRepository('App\Entity\Reviews')->calculateAverageRating(),
        ];

        return $collectedData;
    }
}