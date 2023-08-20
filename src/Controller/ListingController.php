<?php

namespace App\Controller;

use App\Entity\Spaces;
use App\Entity\SpaceImages;
use App\Service\PictureServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpaceImagesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\NewItemCompositeFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ListingController extends AbstractController
{
    #[Route('/listing', name: 'public_listing')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBagInterface,
        PictureServices $pictureService,
        SpaceImagesRepository $spaceImagesRepository
    ): Response {
        $forms = $this->createForm(NewItemCompositeFormType::class, null);
        $forms->handleRequest($request);
        
        if ($forms->isSubmitted() && $forms->isValid()) {
            dd($forms);
            // $space = $this->handleSpaceSteps($formSteps, $request, $entityManager);

            // $this->handleImages($formSteps, $space, $pictureService, $spaceImagesRepository, $parameterBagInterface);

            // $entityManager->persist($space);
            // $entityManager->flush();

            // return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('listing/index.html.twig', compact('forms'));
    }

    private function handleSpaceSteps($formSteps, $request, $entityManager): Spaces {
        // Space
        $formStep1 = $formSteps->getViewData()["formStep1"];
        $formStep1->setUser($this->getUser());
        
        // Adresse
        $formStep2 = $formSteps->getViewData()["formStep2"];
        $formStep2->setSpace($formStep1);

        // SpaceEquipmentLink
        $formStep3 = $formSteps->getViewData()["formStep3"];
        $this->getUser()->setStatus($request->request->all()['steps_form']['formStep3']['status']);
        $formStep3->setSpace($formStep1);

        // SpaceTranslation
        $formStep5 = $formSteps->getViewData()["formStep5"];
        $formStep5->setSpace($formStep1);

        $formStep1->addAdresse($formStep2);
        $formStep1->addContent($formStep5);

        $entityManager->persist($formStep1);
        $entityManager->flush();

        return $formStep1;
    }

    private function handleImages($formSteps, $space, $pictureService, $spaceImagesRepository, $parameterBagInterface) {
        // SpaceImages
        $formStep4 = $formSteps->get('formStep4')->get('imagePath');
        
        $givenNameInitials = substr($this->getUser()->getGivenName(), 0, 2);
        $folder = $this->getUser()->getFamilyName() . $givenNameInitials . '-' . $this->getUser()->getId() . '-' . $space->getId();
        
        $initialOrder = count($spaceImagesRepository->findAll());
        
        foreach ($formStep4->getData() as $index => $file) {
            $fichier = $pictureService->add($file, $folder);
            
            $order = $initialOrder + $index + 1;
            
            $spaceImage = new SpaceImages();
            $spaceImage->setImagePath($fichier);
            $spaceImage->setSortOrder($order);
            $spaceImage->setSpace($space);

            $space->addImage($spaceImage);
        }
    }
}
