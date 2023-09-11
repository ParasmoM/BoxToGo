<?php

namespace App\Controller;

use App\Entity\Images;
use App\DTO\FormAddNewSpaceModel;
use App\Services\PictureServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Combined\FormAddNewSpaceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ListingController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    #[Route('/listing', name: 'public_listing')]
    public function index(
        Request $request,
        ParameterBagInterface $parameterBagInterface,
        PictureServices $pictureService,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());

            return $this->redirectToRoute('app_error_403');
        }
        
        $model = new FormAddNewSpaceModel();
        // Space
        $model->getSpace()->setPrice(100);
        $model->getSpace()->setSurface(100);
        $model->getSpace()->setEntryWidth(100);
        $model->getSpace()->setEntryLength(100);
        $model->getSpace()->setFloorLevel("Rez de chaussée");
        $model->getSpace()->setConditionStatus("neuf");
        $model->getSpace()->setOwnedByUser($this->getUser()); // ! Je vais garder ça 
        // Adresse 
        $model->getAdresse()->setCountry('Belgique');
        $model->getAdresse()->setCity('Liège');
        $model->getAdresse()->setStreetNumber(1);
        $model->getAdresse()->setStreet('Saint Laurent');
        $model->getAdresse()->setPostalCode(4000);
        $forms = $this->createForm(FormAddNewSpaceType::class, $model);
        // dd($forms, $request);
        $forms->handleRequest($request);
        if ($forms->isSubmitted() && $forms->isValid()) {
            $user = $this->getUser();
            
            if (!empty($user->getRoles())) {
                if (!in_array('ROLE_OWNER', $user->getRoles())) {
                    $user->setRoles(['ROLE_USER', 'ROLE_OWNER']);
                }
            } else {
                // Le tableau des rôles est vide
                $user->setRoles(['ROLE_USER']);
            }
            
            $dataArray = $this->extractFormData($forms->getData(), $request);
        
            // Persist and flush space to ensure it has an ID
            $this->em->persist($dataArray['space']);
            $this->em->flush();
        
            // Now handle images since space has an ID
            $this->handleNewImages($dataArray, $pictureService);
        
            $dataArray['adresse']->setSpaces($dataArray['space']);
            $dataArray['amenity']->setSpaces($dataArray['space']);
            $dataArray['content']->setTitle($dataArray['language'], $dataArray['title']);
            $dataArray['content']->setDescription($dataArray['language'], $dataArray['description']);
            $dataArray['content']->setSpaces($dataArray['space']);
        
            // Persist the other entities
            $this->em->persist($dataArray['adresse']);
            $this->em->persist($dataArray['amenity']);
            $this->em->persist($dataArray['content']);
            
            // Flush again to save the other entities
            $this->em->flush();
        
            return $this->redirectToRoute('public_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('listing/index.html.twig', compact('forms'));
    }

    private function extractFormData($formData, Request $request) {
        return [
            'space' => $formData->getSpace(),
            'adresse' => $formData->getAdresse(),
            'amenity' => $formData->getAmenity(),
            'image' => $formData->getImage(),
            'content' => $formData->getContent(),
            'language' => $request->request->all()['form_add_new_space']['content']['language'],
            'title' => $request->request->all()['form_add_new_space']['content']['title'],
            'description' => $request->request->all()['form_add_new_space']['content']['description'],
            'newImages' => $request->files->all()['form_add_new_space']['image']['imagePath']
        ];
    }

    private function handleNewImages($dataArray, $pictureService) {
        $folder = 'Archives/Spaces/' . $dataArray['space']->getId() . '/Galleries';
        $initialOrder = count($this->em->getRepository(Images::class)->findBy(['spaces' => $dataArray['space']]));

        foreach ($dataArray['newImages'] as $index => $file) {
            $fichier = $pictureService->add($file, $folder);
            
            $order = $initialOrder + $index + 1;

            // Create a new Image object for each file
            $image = new Images();
            $image->setSpaces($dataArray['space']);
            $image->setImagePath($fichier);
            $image->setSortOrder($order);
            
            $this->em->persist($image);
        }
    }
}
