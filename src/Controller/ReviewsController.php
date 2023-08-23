<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Form\ReviewsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewsController extends AbstractController
{
    #[Route('/reviews/{id}', name: 'app_reviews')]
    public function index(
        Request $request,
        Spaces $space,
        EntityManagerInterface $entityManager,
    ): Response {
        $review = new Reviews();
        $review->setUser($this->getUser());
        $review->setSpace($space);
        
        $form = $this->createForm(ReviewsFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reviews/index.html.twig', compact('form'));
    }
}
