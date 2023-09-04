<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Reviews;
use App\Entity\SpaceCategories;
use App\Entity\Spaces;
use App\Repository\SpacesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpaceCategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    #[Route('/', name: 'public_home')]
    public function index(): Response {
        // $this->createReviewAction();
        // dd('stop');
        $categories = $this->em->getRepository(SpaceCategories::class)->findBy([], ['name' => 'ASC']);
        $allSpaces = $this->em->getRepository(Spaces::class)->findBy([], ['registrationDate' => 'ASC']);
        return $this->render('home/index.html.twig', compact('categories', 'allSpaces'));
    }

    public function createReviewAction()
    {
        $faker = Factory::create('fr_BE');
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

        foreach ($owners as $host) {
            foreach ($host->getOwner() as $space) {
                foreach ($nonOwners as $user) {
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
}
