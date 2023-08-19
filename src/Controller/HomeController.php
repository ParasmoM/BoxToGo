<?php

namespace App\Controller;

use App\Repository\SpaceCategoriesRepository;
use App\Repository\SpacesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'public_home')]
    public function index(
        SpaceCategoriesRepository $spaceCategoriesRepository,
        SpacesRepository $spacesRepository,
    ): Response {
        $categories = $spaceCategoriesRepository->findBy([], ['name' => 'ASC']);
        $allSpaces = $spacesRepository->findBy([], ['registrationDate' => 'ASC']);
        return $this->render('home/index.html.twig', compact('categories', 'allSpaces'));
    }
}
