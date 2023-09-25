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
