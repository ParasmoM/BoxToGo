<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Entity\SpaceTypes;
use App\Entity\Reservations;
use App\Form\searchBarFormType;
use App\Model\SearchData;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    #[Route('/', name: 'public_home', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        PaginatorInterface $paginator,
    ): Response {
        $action = $request->query->get('action');
        $typeId = $request->query->get('filter');
        $searchQuery = $request->query->get('search');
        
        $repoSpaces = $this->em->getRepository(Spaces::class);
        
        if (!empty($typeId)) {
            $query = $repoSpaces->findBy(['type' => $typeId], ['createAt' => 'ASC']);

        } elseif (!empty($searchQuery)) {
            $verif = $this->verifySearch($searchQuery);
            
            if ($action === 'search') {
                if ($verif['isNumber']) {

                    $query = $repoSpaces->findSpacesByPostalOrCity($searchQuery);

                } elseif ($verif['isSingleWord']) {

                    $types = $this->em->getRepository(SpaceTypes::class)->findSpaceTypeByName($searchQuery);
                    $zipOrCity = $repoSpaces->findSpacesByPostalOrCity($searchQuery);
                    $status = $repoSpaces->findSpacesByUserStatus($searchQuery);
                    
                    if (!empty($types)) {
                        foreach ($types as $type) {
                            $query = $repoSpaces->findBy(['type' =>  $type->getId()]) ?? false;
                        }
                    }

                    if ($zipOrCity) {
                        $query = $zipOrCity;
                    }

                    if ($status) {
                        $query = $status;
                    }

                } elseif ($verif['isMultipleWords']) {
                    $words = preg_split('/\s+/', $searchQuery);
                    $words = array_unique($words);
                    $query = [];
                    
                    foreach ($words as $word) {
                        $types = $this->em->getRepository(SpaceTypes::class)->findSpaceTypeByName($word);
                        $zipOrCity = $repoSpaces->findSpacesByPostalOrCity($word);
                    
                        if (!empty($types)) {
                            foreach ($types as $type) {
                                $results = $repoSpaces->findBy(['type' => $type->getId()]) ?? [];
                                $query = array_merge($query, $results);
                            }
                        }
                    
                        if ($zipOrCity) {
                            $query = array_merge($query, $zipOrCity);
                        }
                    }
                }
                
            } elseif ($action === 'reset') {
                // réinitialiser le formulaire ou faire quelque chose d'autre
            }

        } else {
            $query = $repoSpaces->findBy([], ['createAt' => 'ASC']);
        }
        // dd($query);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        // Vérifier si la requête est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            // dd($request->query, $typeId, $page);
            return new JsonResponse([
                'content' => $this->renderView('components/_card-space.html.twig', [
                    'allSpaces' => $pagination,
                ]),
                'pagination' => $this->renderView('components/_pagination.html.twig', [
                    'allSpaces' => $pagination,
                ]),
            ]);
        }

        return $this->render('home/index.html.twig', [
            'spaceTypes' => $this->em->getRepository(SpaceTypes::class)->findBy([], ['name' => 'ASC']),
            'allSpaces' => $pagination,
            // 'form' => $form
        ]);
    }

    public function verifySearch($searchBar)
    {
        $search = $searchBar;
        $result = [];

        // Sépare la chaîne en mots
        $words = preg_split('/\s+/', $search);

        $numericCount = 0;
        $stringCount = 0;

        foreach ($words as $word) {
            if (is_numeric($word)) {
                $numericCount++;
            } else {
                $stringCount++;
            }
        }

        // Vérification si c'est un nombre
        $result['isNumber'] = $numericCount > 0 && $stringCount === 0;

        // Vérification si c'est un seul mot
        $result['isSingleWord'] = count($words) === 1;

        // Vérification si c'est en plusieurs mots
        $result['isMultipleWords'] = $stringCount > 1;

        return $result;
    }

    public function separateOwnersAndNonOwners()
    {
        $owners = [];
        $nonOwners = [];
        
        $allUsers = $this->em->getRepository(Users::class)->findAll();

        foreach ($allUsers as $user) {
            $roles = $user->getRoles();  

            if (in_array('ROLE_OWNER', $roles)) {
                $owners[] = $user;  
            } else {
                $nonOwners[] = $user;  
            }
        }

        return $users = [
            'host' => $owners,
            'user' => $nonOwners
        ];
    }

    public function createReviewAction($data)
    {
        $faker = Factory::create('fr_BE');

        foreach ($data['host'] as $host) {
            foreach ($host->getOwner() as $space) {
                foreach ($data['user'] as $user) {
                    $review = New Reviews();
                    $review->setComment($faker->sentences(5, true));
                    $review->setRating($faker->randomElement([1, 2, 3, 4, 5]));
                    $review->setUser($user);
                    $review->setSpace($space);
                    $this->em->persist($review);
                    $space->addReview($review);
                }
            }
        }

        $this->em->flush();
    }

    public function createResaction($data)
    {
        $faker = Factory::create('fr_BE');

        $yesterday = new \DateTime('yesterday');
        $lastWeek = new \DateTime('-1 week');

        foreach ($data['host'] as $host) {
            foreach ($host->getOwner() as $space) {
                foreach ($data['user'] as $user) {
                    $resa = new Reservations();
                    $resa->setPrice($space->getPrice());
                    $resa->setSpace($space);
                    $resa->setUser($user);
                    $resa->setDateStart(clone $lastWeek);
                    $resa->setDateEnd(clone $yesterday);
                    $space->setUser($user);
                    $space->setStatus('busy');
                    $user->addRenter($space);

                    $this->em->persist($resa);
                }
            }
        }
        $this->em->flush();
    }
}
