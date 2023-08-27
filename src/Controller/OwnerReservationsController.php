<?php

namespace App\Controller;


use App\Entity\Spaces;
use App\Entity\SpaceImages;
use App\DTO\FormEditSpaceModel;
use App\Service\PictureServices;
use App\Repository\UsersRepository;
use App\Repository\SpacesRepository;
use App\Form\Combined\FormEditSpaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OwnerReservationsController extends AbstractController
{
    #[Route('/owner/annonces', name: 'owner_annonces')]
    public function annonce(
        Request $request,
    ): Response {
        return $this->render('owner_reservations/annonces.html.twig');
    }

    #[Route('/owner/reservations', name: 'owner_reservations')]
    public function reservation(
        Request $request,
    ): Response {
        return $this->render('owner_reservations/index.html.twig');
    }

    #[Route('/owner/edit/{id}', name: 'owner_edit')]
    public function edit(
        Request $request,
        Spaces $space,
        EntityManagerInterface $entityManager,
        PictureServices $pictureService,
        SpacesRepository $spacesRepository,
        UsersRepository $usersRepository,
    ): Response {
        if (!$this->getUser()) return $this->redirectToRoute('public_home');
        if (!$space->getHost() == $this->getUser()) return $this->redirectToRoute('public_home');

        $galleries = $entityManager->getRepository(SpaceImages::class)->findBy([], ['sortOrder' => 'ASC']);
        
        $user = $space->getHost();
        $DTO_MODEL = new FormEditSpaceModel();
        $DTO_MODEL->hydrate($space);
        // dd($DTO_MODEL);
        $form = $this->createForm(FormEditSpaceType::class, $DTO_MODEL);
        // Si vous voulez définir la valeur du champ status basée sur une propriété du $user
        $statusFromUser = $user->getStatus();
        if ($statusFromUser) {
            $form->get('equipment')->get('status')->setData($statusFromUser);
        } else {
            $form->get('equipment')->get('status')->setData('Particulier');
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status = $request->request->all()['form_edit_space']['equipment']['status'];
            // dd($_POST);
            if (isset($_POST['images'])) {
                $oldOrder = $_POST['images'];
                $newOrder = [];
    
                foreach($oldOrder as $key => $value) {
                    $imageEntity = $entityManager->getRepository(SpaceImages::class)->findOneBy(['id' => $key]);
    
                    $imageEntity->setSortOrder($value);
                    $entityManager->persist($imageEntity);
                    $entityManager->flush();
                }
            }
            // dd($form, $form->getData()->getSpace(), $space);
            if ($form->getData()->getGalleries()->getImagePath()) {
                $this->handleNewImages($form, $request, $pictureService, $entityManager);
                
            }

            $user->setStatus($status);
            $usersRepository->save($user);
            $spacesRepository->save($space);
            return $this->redirectToRoute('owner_annonces', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('owner_reservations/edit.html.twig', compact('space', 'form', 'galleries'));
    }

    private function handleNewImages($dataArray, $request, $pictureService, EntityManagerInterface $entityManager) {
        $folder = 'Archives/Spaces/' . $dataArray->getData()->getSpace()->getId() . '/Galleries';
        $initialOrder = count($entityManager->getRepository(SpaceImages::class)->findBy(['space' =>  $dataArray->getData()->getSpace()]));
        $dataImage = $request->files->all()['form_edit_space']['galleries']['imagePath'];
        $fichier = $pictureService->add($dataImage, $folder);
        
        $order = $initialOrder + 1;

        // Create a new Image object for each file
        $image = new SpaceImages();
        $image->setSpace($dataArray->getData()->getSpace());
        $image->setImagePath($fichier);
        $image->setSortOrder($order);
        
        $entityManager->persist($image);
        $entityManager->flush();
    }
}
