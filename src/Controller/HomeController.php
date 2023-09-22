<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Spaces;
use DateTimeImmutable;
use App\Entity\Reviews;
use App\Entity\SpaceTypes;
use App\Entity\Reservations;
use App\Model\SearchBarHome;
use App\Form\SearchBarHomeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\Constraint\IsTrue;
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
        $updateStatusResa = $this->em->getRepository(Reservations::class)->findBy(['status' => 'busy']);
        foreach ($updateStatusResa as $entity) {
            $currentDate = new DateTimeImmutable();
            
            if($currentDate > $entity->getDateEnd()) {
                $entity->setStatus('finished');
            }
        }
        $this->em->flush();

        $updateSpaces = $this->em->getRepository(Spaces::class)->findBy([]);

        // dd($updateSpaces[0]->getReservations(), $updateSpaces[0]->get);

        $action = $request->query->get('action');
        $typeId = $request->query->get('filter');

        $repoSpaces = $this->em->getRepository(Spaces::class);
        
        if (!empty($typeId)) {
            $query = $repoSpaces->findBy(['type' => $typeId], ['createAt' => 'ASC']);

        } else {
            $query = $repoSpaces->findBy([], ['createAt' => 'ASC']);
        }
        
        $searchBar = new SearchBarHome();

        $form = $this->createForm(SearchBarHomeFormType::class, $searchBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $verif = $this->verifySearch($searchBar->getSearch());
            $searchQuery = $searchBar->getSearch();
            dd(1);
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
                // dd($query, $status, $zipOrCity);

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
            
            if ($searchBar->getDateStart()) {
                foreach ($query as $key => $space) {
                    if ($space->getRentedByUser() !== null) {
                        foreach ($space->getReservations() as $resa) {
                            if($resa->getStatus() !== 'finished') {
                                if (
                                    ($searchBar->getDateStart() >= $resa->getDateStart() && $searchBar->getDateStart() <= $resa->getDateEnd())
                                    || ($searchBar->getDateStart() < $resa->getDateStart() && $searchBar->getDateEnd() >= $resa->getDateStart())
                                ) {
                                    unset($query[$key]);
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($request->query);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        // Vérifier si la requête est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            // dd($request->query, $typeId, $pagination);
            return new JsonResponse([
                'content' => $this->renderView('components/_card-space.html.twig', [
                    'pagination' => $pagination,
                ]),
                'pagination' => $this->renderView('components/_pagination.html.twig', [
                    'pagination' => $pagination,
                ])
            ]);
        }

        return $this->render('home/index.html.twig', [
            'spaceTypes' => $this->em->getRepository(SpaceTypes::class)->findBy([], ['id' => 'ASC']),
            'pagination' => $pagination,
            'formSearch' => $form
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
}
