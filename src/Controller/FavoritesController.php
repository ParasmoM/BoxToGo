<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Spaces;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoritesController extends AbstractController
{
    #[Route('/fetch/favorites', name: 'fetch_favorites', methods: ['POST'])]
    public function fetch(
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $spaceId = $data['spaceId'];

        $user = $entityManager->getRepository(Users::class)->find($userId);
        $space = $entityManager->getRepository(Spaces::class)->find($spaceId);

        if (!$user || !$space) {
            return $this->json(['message' => 'Utilisateur ou espace introuvable'], Response::HTTP_BAD_REQUEST);
        }

        $favori = $entityManager->getRepository(Favoris::class)->findOneBy([
            'user' => $user,
            'space' => $space,
        ]);

        if ($favori) {
            $entityManager->remove($favori);
            $message = 'Espace retiré des favoris';
        } else {
            $favori = new Favoris();
            $favori->setUser($user);
            $favori->setSpace($space);
            $entityManager->persist($favori);
            $message = 'Espace ajouté aux favoris';
        }

        $entityManager->flush();

        return $this->json(['message' => $message]);
    }
    
    #[Route('/favorites', name: 'app_favorites')]
    public function index(
        Request $request,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        $user = $this->getUser();

        $favorites = $favorisRepository->findBy(['user' => $user->getId()], ['id' => 'ASC']);
        
        return $this->render('favorites/index.html.twig', compact('favorites'));
    }
}
