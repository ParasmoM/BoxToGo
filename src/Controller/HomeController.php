<?php

namespace App\Controller;

use App\Entity\Reservations;
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
        // $data = $this->separateOwnersAndNonOwners();

        // $this->createReviewAction($data);

        // $this->createResaction($data);

        
        // dd('success');
        $categories = $this->em->getRepository(SpaceCategories::class)->findBy([], ['name' => 'ASC']);
        $allSpaces = $this->em->getRepository(Spaces::class)->findBy([], ['registrationDate' => 'ASC']);
        return $this->render('home/index.html.twig', compact('categories', 'allSpaces'));
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
