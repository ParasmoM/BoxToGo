<?php

namespace App\Controller\Admin;

use App\Entity\Reviews;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    #[Route('/admin/comments', name: 'admin_comments')]
    public function comments(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $repository = $this->em->getRepository(Reviews::class);

        $query = $repository->findAll();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/comments/comment_and_rating.html.twig', [
            'dataReviews' => $pagination,
        ]);
    }

    #[Route('/admin/comment/detail/{id}', name: 'admin_comment_detail')]
    public function commentDetail(Reviews $review): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $repository = $this->em->getRepository(Reviews::class);

        return $this->render('admin/comments/_comment_detail.html.twig', [
            'data' => $repository->findOneBy(['id' => $review->getId()]),
        ]);
    }
}