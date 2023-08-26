<?php

namespace App\Controller;

use App\DTO\FormAddNewSpaceModel;
use App\Entity\Contents;
use App\Entity\Spaces;
use App\Entity\SpaceImages;
use App\Form\Combined\FormAddNewSpaceType;
use App\Service\PictureServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpaceImagesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\NewItemCompositeFormType;
use Masterminds\HTML5\Entities;
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
        $model = new FormAddNewSpaceModel();
        // Space
        $model->getSpace()->setPrice(100);
        $model->getSpace()->setSurface(100);
        $model->getSpace()->setEntryWidth(100);
        $model->getSpace()->setEntryLength(100);
        $model->getSpace()->setFloorPosition("Rez de chaussée");
        $model->getSpace()->setItemCondition("neuf");
        $model->getSpace()->setHost($this->getUser()); // ! Je vais garder ça 
        // Adresse 
        $model->getAdresse()->setCountry('Belgique');
        $model->getAdresse()->setCity('Liège');
        $model->getAdresse()->setStreetNumber(1);
        $model->getAdresse()->setStreet('Saint Laurent');
        $model->getAdresse()->setPostalCode(4000);

        $forms = $this->createForm(FormAddNewSpaceType::class, $model);
        $forms->handleRequest($request);
        if ($forms->isSubmitted() && $forms->isValid()) {
            $user = $this->getUser();
        
            if (!in_array('ROLE_OWNER', $user->getRoles())) {
                $user->setRoles(['ROLE_USER', 'ROLE_OWNER']);
            }
        
            $dataArray = $this->extractFormData($forms->getData(), $request);
        
            // Persist and flush space to ensure it has an ID
            $entityManager->persist($dataArray['space']);
            $entityManager->flush();
        
            // Now handle images since space has an ID
            $this->handleNewImages($dataArray, $pictureService, $entityManager);
        
            $dataArray['adresse']->setSpace($dataArray['space']);
            $dataArray['equipment']->setSpace($dataArray['space']);
            $dataArray['content']->setTitle($dataArray['language'], $dataArray['title']);
            $dataArray['content']->setDescription($dataArray['language'], $dataArray['description']);
            $dataArray['content']->setSpace($dataArray['space']);
        
            // Persist the other entities
            $entityManager->persist($dataArray['adresse']);
            $entityManager->persist($dataArray['equipment']);
            $entityManager->persist($dataArray['content']);
            
            // Flush again to save the other entities
            $entityManager->flush();
        
            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('listing/index.html.twig', compact('forms'));
    }

    private function extractFormData($formData, Request $request) {
        return [
            'space' => $formData->getSpace(),
            'adresse' => $formData->getAdresse(),
            'equipment' => $formData->getEquipment(),
            'image' => $formData->getImage(),
            'content' => $formData->getContent(),
            'language' => $request->request->all()['form_add_new_space']['content']['language'],
            'title' => $request->request->all()['form_add_new_space']['content']['title'],
            'description' => $request->request->all()['form_add_new_space']['content']['description'],
            'newImages' => $request->files->all()['form_add_new_space']['image']['imagePath']
        ];
    }

    private function handleNewImages($dataArray, $pictureService, EntityManagerInterface $entityManager) {
        $folder = 'Archives/Spaces/' . $dataArray['space']->getId() . '/Galleries';
        $initialOrder = count($entityManager->getRepository(SpaceImages::class)->findBy(['space' => $dataArray['space']]));

        foreach ($dataArray['newImages'] as $index => $file) {
            $fichier = $pictureService->add($file, $folder);
            
            $order = $initialOrder + $index + 1;

            // Create a new Image object for each file
            $image = new SpaceImages();
            $image->setSpace($dataArray['space']);
            $image->setImagePath($fichier);
            $image->setSortOrder($order);
            
            $entityManager->persist($image);
        }
    }
}
