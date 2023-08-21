<?php

namespace App\Controller;

use App\Entity\Contents;
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

            $space = $this->handleSpaceSteps($forms, $request, $entityManager);

            $this->handleImages($forms, $space, $pictureService, $spaceImagesRepository, $parameterBagInterface);

            $entityManager->persist($space);
            $entityManager->flush();

            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('listing/index.html.twig', compact('forms'));
    }

    private function handleSpaceSteps($forms, $request, $entityManager): Spaces {
        $user = $this->getUser();
        $roles = $user->getRoles();
        
        // Space
        if (!in_array('ROLE_OWNER', $roles)) {
            $user->setRoles(['ROLE_USER', 'ROLE_OWNER']);
        }

        $formStep1 = $forms->getData()["step1"];
        $formStep1->setUser($this->getUser());
        
        // Adresse
        $formStep2 = $forms->getData()["step2"];
        $formStep2->setSpace($formStep1);

        // SpaceEquipmentLink
        $formStep3 = $forms->getData()["step3"];
        
        if (count($request->request->all()['new_item_composite_form']['step3']) === 2) {
            $status = $request->request->all()['new_item_composite_form']['step3']['status'];
            $user->setStatus($status);
        }
        $formStep3->setSpace($formStep1);
        
        // SpaceTranslation
        $formStep5 = $forms->getData()["step5"];
        $language = $forms->getData()["step5"]["language"];
        $title = $forms->getData()["step5"]["title"];
        $description = $forms->getData()["step5"]["description"];
        $contents = new Contents();
        $contents->setTitle($language, $title);
        $contents->setDescription($language, $description);
        $contents->setSpace($formStep1);
        
        $formStep1->addAdresse($formStep2);
        $formStep1->setContent($contents);

        $entityManager->persist($formStep1);
        $entityManager->flush();

        return $formStep1;
    }

    private function handleImages($forms, $space, $pictureService, $spaceImagesRepository, $parameterBagInterface) {
        // SpaceImages
        $formStep4 = $forms->get('step4')->get('imagePath');
        
        $folder = 'Archives/Spaces/' . $space->getId() . '/Galleries';
        
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
