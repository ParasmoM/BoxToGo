<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Spaces;
use App\Entity\FavoriteSpaces;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\FavoriteSpacesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoritesController extends AbstractController
{
    #[Route('/fetch/favorites', name: 'fetch_favorites', methods: ['POST'])]
    public function fetch(
        Request $request,
        EntityManagerInterface $entityManager,
        FavoriteSpacesRepository $favoritesRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $spaceId = $data['spaceId'];

        $user = $entityManager->getRepository(User::class)->find($userId);
        $space = $entityManager->getRepository(Spaces::class)->find($spaceId);
        // dd($data);
        if (!$user || !$space) {
            return $this->json(['message' => 'Utilisateur ou espace introuvable'], Response::HTTP_BAD_REQUEST);
        }

        $favori = $entityManager->getRepository(FavoriteSpaces::class)->findOneBy([
            'user' => $user,
            'spaces' => $space,
        ]);
        // dd($favori);
        if ($favori) {
            $entityManager->remove($favori);
            $message = 'Espace retiré des favoris';
        } else {
            $favori = new FavoriteSpaces();
            $favori->setUser($user);
            $favori->setSpaces($space);
            $entityManager->persist($favori);
            $message = 'Espace ajouté aux favoris';
        }

        $entityManager->flush();

        $user = $this->getUser();

        $favorites = $favoritesRepository->findBy(['user' => $user->getId()], ['id' => 'ASC']);
        // dd($favorites);
        return new JsonResponse([
            'content' => $this->renderView('favorites/_card.html.twig', [
                'favorites' => $favorites,
            ])
        ]);
    }

    #[Route('/fetch/delete/favorites', name: 'fetch_favorites_delete', methods: ['POST'])]
    public function fetchDelete(
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {

    }
    
    #[Route('/favorites', name: 'app_favorites')]
    public function index(
        Request $request,
        FavoriteSpacesRepository $favoritesRepository,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            return $this->redirectToRoute('app_error_403');
        }
        
        // if (!$this->getUser()) return $this->redirectToRoute('public_home');
        $user = $this->getUser();

        $favorites = $favoritesRepository->findBy(['user' => $user->getId()], ['id' => 'ASC']);
        
        // $pagination = $paginator->paginate(
        //     $favorites,
        //     $request->query->getInt('page', 1),
        //     25
        // );

        return $this->render('favorites/index.html.twig', compact('favorites'));
    }
}
