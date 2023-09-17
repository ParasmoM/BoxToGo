<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Entity\Reservations;
use App\Entity\SpaceAmenities;
use App\Entity\SpaceTypes;
use App\Form\Admin\searchBarAdminFormType;
use App\Model\SearchDataAdmin;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Stripe\Review;
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

    #[Route('/admin', name: 'app_admin')]
    public function analytics(Request $request): Response
    {
        $datas = $this->gatherData();
        $datas[] = [
            'name' => 'ratings',
            'nbr' => $this->em->getRepository(Reviews::class)->calculateAverageRating()
        ];

        $repoType = $this->em->getRepository(SpaceTypes::class);
        $category = $repoType->findBy([]);
        
        $repoAmenity = $this->em->getRepository(SpaceAmenities::class);
        $amenity = $repoAmenity->findBy([]);

        $searchBar = new SearchDataAdmin();

        $form = $this->createForm(searchBarAdminFormType::class, $searchBar, [
            'categories' => $category,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = [];
            // Si seulement Category est de définis retourne des instances des SPACES
            if (isset($searchBar->category) && !empty($searchBar->category) && !isset($searchBar->status)) {

                $query = $this->em->getRepository(Spaces::class)->findBy(['type' => $searchBar->getCategory()]);
                $data[] = 'space';

                // Si seulement Status est de definis retourne des instances des SPACES et USER
            } else if (isset($searchBar->status) && !empty($searchBar->status) && !isset($searchBar->category)) {
                dd(2);

                // Si le deux sont definis retourne des instances des SPACES
            } else if (isset($searchBar->status) && !empty($searchBar->status) && !isset($searchBar->category)) {
                dd(2);
            }

            $pagination = $this->paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );

            $data[] = $pagination;
            // dd($data);
            return $this->render('admin/search.html.twig', [
                'form' => $form,
                'pagination' => $data,
            ]);
        }
        // dd($datas);
        return $this->render('admin/analytics.html.twig', [
            'datas' => $datas,
            'categories' => $category,
            'amenities' => $amenity,
            'form' => $form,
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(Request $request): Response
    {
        $repository = $this->em->getRepository(User::class);
        $query = $repository->findAll();
        // dd($query);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/users.html.twig', [
            'dataUsers' => $pagination,
        ]);
    }

    #[Route('/admin/notices', name: 'app_admin_notices')]
    public function notices(Request $request): Response
    {
        $repository = $this->em->getRepository(Spaces::class);
        $query = $repository->findAll();
        // dd($query);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/notices.html.twig', [
            'dataSpaces' => $pagination,
        ]);
    }

    #[Route('/admin/resa', name: 'app_admin_resa')]
    public function resa(Request $request): Response
    {
        $repository = $this->em->getRepository(Reservations::class);
        $query = $repository->findAll();
        // dd($query);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
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
            10
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

    public function gatherData()
    {
        $entities = [
            'App\Entity\User' => 'users',
            'App\Entity\Spaces' => 'announcements',
            'App\Entity\Reservations' => 'reservations',
            'App\Entity\Reviews' => 'comments',
        ];

        $datas = [];

        foreach ($entities as $entityClass => $name) {
            $repo = $this->em->getRepository($entityClass);
            $instances = $repo->findBy([], ['createAt' => 'DESC'], 5);
            $count = count($repo->findBy([], ['createAt' => 'DESC']));

            $data = [
                'name' => $name,
                'instances' => $instances,
                'nbr' => $count,
            ];

            if (method_exists($repo, 'countByPeriod')) {
                $data['new'] = $repo->countByPeriod();
            }

            if ($entityClass === 'Reviews') {
                if (method_exists($repo, 'calculateAverageRating')) {
                    $data['ratings'] = $repo->calculateAverageRating();
                }
            }

            $datas[] = $data;
        }

        return $datas;
    }

}
