<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController
{
    #[Route('/error/403', name: 'app_error_403')]
    public function error_403(Request $request): Response
    {
        
        return $this->render('error/error.html.twig', [
            'typeError' => 403,
            'message' => $request->getSession()->get('errorMessage'),
        ]);
    }

    #[Route('/error/404', name: 'app_error_404')]
    public function error_404(Request $request): Response
    {
        
        return $this->render('error/error.html.twig', [
            'typeError' => 404,
            'message' => $request->getSession()->get('errorMessage'),
        ]);
    }
}
