<?php

namespace App\Controller;

use App\Entity\Spaces;
use App\Form\Step1FormType;
use App\Form\StepsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListingController extends AbstractController
{
    #[Route('/listing', name: 'app_listing')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // // $step1 = new Spaces($this->getUser());
        // $step1->setUser($this->getUser());
        $formSteps = $this->createForm(StepsFormType::class, null);
        $formSteps->handleRequest($request);

        if ($formSteps->isSubmitted() && $formSteps->isValid()) {
            // Space 
            dd($formSteps);
            $formStep1 = $formSteps->getViewData()["formStep1"];
            $formStep1->setUser($this->getUser());
            
            $formStep2 = $formSteps->getViewData()["formStep2"];
            $formStep2->setSpace($formStep1);
            
            $formStep1->addAdresse($formStep2);


            $entityManager->persist($formStep1);
            $entityManager->persist($formStep2);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('listing/index.html.twig', compact('formSteps'));
    }
}
