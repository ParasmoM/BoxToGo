<?php

namespace App\Controller\Admin;

use Stripe\Review;
use App\Entity\User;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Entity\SpaceTypes;
use App\Entity\Reservations;
use App\Entity\SpaceAmenities;
use App\Model\SearchDataAdmin;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Admin\SearchDataAdminFormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    #[Route('/admin', name: 'app_admin', methods: ['GET', 'POST'])]
    public function analytics(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

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
            $newAdmin = $repoUser->findOneBy(['email' => $formNewAdmin->getData()['email']]);

            $arrayRoles = $newAdmin->getRoles();

            if (!in_array('ROLE_ADMIN', $arrayRoles)) {
                array_push($arrayRoles, 'ROLE_ADMIN');
                $newAdmin->setRoles($arrayRoles);
                $repoUser->save($newAdmin);
            }
                        
            return $this->redirectToRoute('app_admin');
        }

        // Crée un formulaire destiné à l'ajout d'un nouveau type d'espace
        $formSpaceType = $this->createdForm('SpaceTypes', $request);
        if ($formSpaceType->isSubmitted() && $formSpaceType->isValid()) {
            
            $this->em->persist($newSpaceType);
            $this->em->flush();
            
            return $this->redirectToRoute('app_admin');
        }

        // Crée un formulaire destiné à l'ajout de nouveaux équipements pour un espace
        $formAmenities = $this->createdForm('SpaceAmenities', $request);
        if ($formAmenities->isSubmitted() && $formAmenities->isValid()) {
            
            $this->em->persist($newAmenity);
            $this->em->flush();
            
            return $this->redirectToRoute('app_admin');
        }
        
        return $this->render('admin/analytics/analytics.html.twig', [
            'datas' => $datas,
            'entityData' => $entityData,
            'formSearchBar' => $formSearchBar,
            'formNewAdmin' => $formNewAdmin,
            'formSpaceType' => $formSpaceType,
            'formAmenities' => $formAmenities,
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $statusUser = $request->query->get('status');
        $nameUser = $request->query->get('name');
        
        $repository = $this->em->getRepository(User::class);

        if ($statusUser) {
            $query = $repository->findBy(['status' => $statusUser]);
        } elseif ($nameUser) {
            $verif = $this->verifySearch($nameUser);

            if ($verif['isSingleWord']) {
                $firstName = $repository->findBy(['givenName' => $nameUser]);
                $lastName = $repository->findBy(['familyName' => $nameUser]);

                if (!empty($firstName)) {
                    $query[] = $firstName;
                }
                
                if (!empty($lastName)) {
                    $query[] = $lastName;
                }

            } elseif ($verif['isMultipleWords']) {
                $wordsArray = explode(" ", $nameUser);
                if (count($wordsArray) > 2) {
                    $query = $repository->findAll();
                } else {
                    $foundByGivenAndFamilyName = $repository->findBy(['givenName' => $wordsArray[0], 'familyName' => $wordsArray[1]]);
                    $foundByFamilyAndGivenName = $repository->findBy(['givenName' => $wordsArray[1], 'familyName' => $wordsArray[0]]);
                    
                    if (!empty($foundByGivenAndFamilyName)) {
                        $query[] = $foundByGivenAndFamilyName;
                    }
                    
                    if (!empty($foundByFamilyAndGivenName)) {
                        $query[] = $foundByFamilyAndGivenName;
                    }
                }
            } 
            
            if (empty($query)) {
                $query = $repository->findAll();
            }

        } else {
            $query = $repository->findAll();
        }
        
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );
        
        return $this->render('admin/users.html.twig', [
            'dataUsers' => $pagination,
        ]);
    }

    #[Route('/admin/notices', name: 'app_admin_notices')]
    public function notices(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $idCategorie = $request->query->get('id');
        $reference = $request->query->get('ref');

        $repository = $this->em->getRepository(Spaces::class);

        if ($idCategorie) {
            $query = $repository->findBy(['type' => $idCategorie]);
        } elseif ($reference) {
            $query = $repository->findBy(['reference' => $reference]);
        } else {
            $query = $repository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/notices.html.twig', [
            'dataSpaces' => $pagination,
        ]);
    }

    #[Route('/admin/resa', name: 'app_admin_resa')]
    public function resa(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $reference = $request->query->get('ref');

        $repository = $this->em->getRepository(Reservations::class);

        if ($reference) {
            $query = $repository->findBy(['reference' => $reference]);
        } else {
            $query = $repository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/reservation.html.twig', [
            'dataResa' => $pagination,
        ]);
    }

    #[Route('/admin/resa/detail/{id}', name: 'app_admin_resa_detail')]
    public function resaDetail(Reservations $resa): Response
    {
        $repository = $this->em->getRepository(Reservations::class);

        return $this->render('admin/_reservation_detail.html.twig', [
            'data' => $repository->findOneBy(['id' => $resa->getId()]),
        ]);
    }

    #[Route('/admin/comments', name: 'app_admin_comments')]
    public function comments(Request $request): Response
    {
        $repository = $this->em->getRepository(Reviews::class);
        $query = $repository->findAll();
        // dd($query);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/comment_and_rating.html.twig', [
            'dataReviews' => $pagination,
        ]);
    }

    #[Route('/admin/comment/detail/{id}', name: 'app_admin_comment_detail')]
    public function commentDetail(Reviews $review): Response
    {
        $repository = $this->em->getRepository(Reviews::class);

        return $this->render('admin/_comment_detail.html.twig', [
            'data' => $repository->findOneBy(['id' => $review->getId()]),
        ]);
    }

    #[Route('/admin/renter/profile/{id}', name: 'app_admin_renter_profile')]
    public function renterProfile(User $user, Request $request): Response
    {
        $repoReview = $this->em->getRepository(Reviews::class);
        $reviews = $repoReview->findBy(['user' => $user->getId()]);

        $dataReview['pagination'] = $this->paginator->paginate(
            $reviews,
            $request->query->getInt('page1', 1),
            1,
            [
                'pageParameterName' => 'page1'
            ]
        );
        $dataReview['count'] = count($reviews);

        $repoResa = $this->em->getRepository(Reservations::class);
        $resa = $repoResa->findBy(['user' => $user->getId()]);

        $dataResa['pagination'] = $this->paginator->paginate(
            $resa,
            $request->query->getInt('page2', 1),
            1,
            [
                'pageParameterName' => 'page2'
            ]
        );
        $dataResa['count'] = count($resa);
        // dd($dataReview['pagination']);
        return $this->render('admin/_renter_profile.html.twig', [
            'data' => $user,
            'dataReview' => $dataReview,
            'dataResa' => $dataResa,
        ]);
    }

    #[Route('admin/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $arrayRoles = $user->getRoles();

            $key = array_search('ROLE_ADMIN', $arrayRoles);

            if ($key !== false) {
                unset($arrayRoles[$key]);
                $user->setRoles($arrayRoles); 
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
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
     * createdForm
     *
     * Cette fonction crée un formulaire en fonction d'une entité et d'une requête données.
     * Elle cherche la classe d'entité et la classe de formulaire correspondantes dans différents namespaces.
     *
     * @param  mixed $entity  Le nom de l'entité pour laquelle créer un formulaire.
     * @param  mixed $request La requête HTTP.
     * @return \Symfony\Component\Form\FormInterface|null Le formulaire créé ou null si la classe de formulaire ou d'entité n'existe pas.
     * @throws \Exception Si la classe de formulaire n'existe pas.
     */
    public function createdForm($entity, $request)
    {
        // Construire le nom complet de la classe d'entité
        $entityClassName =  'App\Entity\\' . ucfirst($entity);
        
        // Vérifier et créer l'instance d'entité
        if (class_exists($entityClassName)) {
            $instance = new $entityClassName();
        } else {
            $instance = null;
        }

        // Construire le nom complet de la classe de formulaire dans le namespace par défaut
        $formTypeClassName = 'App\Form\\' . ucfirst($entity) . 'FormType';

        // Si la classe de formulaire n'existe pas dans le namespace par défaut, chercher dans un autre namespace (ici 'App\Form\Admin')
        if (!class_exists($formTypeClassName)) {
            $formTypeClassName = 'App\Form\Admin\\' . ucfirst($entity) . 'FormType';
        }

        // Vérifier et créer le formulaire
        if (class_exists($formTypeClassName)) {
            $form = $this->createForm($formTypeClassName, $instance);
            $form->handleRequest($request);
        } else {
            throw new \Exception("Le formulaire $formTypeClassName n'existe pas");
        }

        return $form;
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
    
    /**
     * Vérifie la valeur de la barre de recherche et retourne un tableau associatif 
     * avec des indicateurs comme "isNumber", "isSingleWord" et "isMultipleWords".
     *
     * @param string $searchBar La valeur de la barre de recherche à vérifier.
     * @return array Un tableau associatif contenant des indicateurs.
     */
    public function verifySearch($searchBar)
    {
        $search = $searchBar;
    
        $result = [];
    
        // Utilise preg_split pour séparer la chaîne de recherche en mots individuels,
        // en utilisant des espaces comme délimiteurs
        $words = preg_split('/\s+/', $search);
    
        // Initialise les compteurs pour les valeurs numériques et les chaînes de caractères
        $numericCount = 0;
        $stringCount = 0;
    
        // Parcourt chaque mot pour compter les valeurs numériques et les chaînes de caractères
        foreach ($words as $word) {
            if (is_numeric($word)) {
                $numericCount++;
            } else {
                $stringCount++;
            }
        }
    
        // Vérifie si tous les mots sont numériques
        $result['isNumber'] = $numericCount > 0 && $stringCount === 0;
    
        // Vérifie si la recherche contient un seul mot
        $result['isSingleWord'] = count($words) === 1;
    
        // Vérifie si la recherche contient plusieurs mots qui ne sont pas numériques
        $result['isMultipleWords'] = $stringCount > 1;
    
        // Retourne le résultat sous forme de tableau associatif
        return $result;
    }    
}
