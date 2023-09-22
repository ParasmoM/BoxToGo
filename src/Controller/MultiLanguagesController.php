<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MultiLanguagesController extends AbstractController
{
    #[Route('/change/language/{locale}', name: 'app_change_language')]
    public function changeLanguage($locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);
        
        return $this->redirect($request->headers->get('referer'));
    }
}