<?php

namespace App\Controller;


use Exception;
use App\Entity\Images;
use App\Entity\Spaces;
use App\Entity\Reservations;
use App\DTO\FormEditSpaceModel;
use App\Services\PictureServices;
use App\Repository\UserRepository;
use App\Repository\SpacesRepository;
use App\Form\Combined\FormEditSpaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardHostController extends AbstractController
{
    public function __construct(Private EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    #[Route('/owner/annonces', name: 'dashboard_host_annonces')]
    public function annonce(
        Request $request,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            return $this->redirectToRoute('app_error_403');
        }
        
        return $this->render('dashboard_host/annonces.html.twig');
    }

    #[Route('/owner/reservations', name: 'dashboard_host_reservations')]
    public function reservation(
        Request $request,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            return $this->redirectToRoute('app_error_403');
        }

        $allSpaces = $this->entityManager->getRepository(Spaces::class)->findBy(['ownedByUser' => $this->getUser()->getId()]);

        $reservations = [];
        // dd($allSpaces);
        foreach ($allSpaces as $space) {
            // dd($space->getReservation());
            foreach ($space->getReservations() as $reservation) {
                $reservations[] = $reservation;
            }
        }
        
        return $this->render('dashboard_host/reservations.html.twig', [
            'reservations' => $reservations,
            'userBookings' => $this->entityManager->getRepository(Reservations::class)->findBy(['user' => $this->getUser()->getId()]),
        ]);
    }

    #[Route('/owner/edit/{id}', name: 'dashboard_host_edit')]
    public function edit(
        Request $request,
        Spaces $space,
        EntityManagerInterface $entityManager,
        PictureServices $pictureService,
        SpacesRepository $spacesRepository,
        UserRepository $userRepository,
    ): Response {

        try {

            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }

            if ($this->getUser() != $space->getOwnedByUser() ) {
                throw new Exception("Accès refusé : Vous tentez d'accéder à une page réservée à un autre utilisateur.");
            }

        } catch (UserNotAuthenticatedException $e) {
            
            $request->getSession()->set('errorMessage', $e->getMessage());
            
            return $this->redirectToRoute('app_error_403');
        } catch (Exception $e) {
            
            $request->getSession()->set('errorMessage', $e->getMessage());
            
            return $this->redirectToRoute('app_error_403');
        }

        // if (!$this->getUser()) return $this->redirectToRoute('public_home');
        // if (!$space->getOwnedByUser() == $this->getUser()) return $this->redirectToRoute('public_home');

        $galleries = $entityManager->getRepository(Images::class)->findBy(['spaces' => $space->getId()], ['sortOrder' => 'ASC']);
        
        $user = $space->getOwnedByUser();
        $DTO_MODEL = new FormEditSpaceModel();
        // dd($space);
        $DTO_MODEL->hydrate($space);
        // dd($DTO_MODEL);
        $form = $this->createForm(FormEditSpaceType::class, $DTO_MODEL);
        // Si vous voulez définir la valeur du champ status basée sur une propriété du $user
        $statusFromUser = $user->getStatus();
        if ($statusFromUser) {
            $form->get('amenity')->get('status')->setData($statusFromUser);
        } else {
            $form->get('amenity')->get('status')->setData('Particulier');
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $request->request->all()['form_edit_space']['amenity']['status'];
            $adresse =  $form->getData()->getAdresse();
            $equipments =  $form->getData()->getAmenity();
            // $adresse =  $form->getData()->getAdresse();
            // dd($form, $form->getData()->getAdresse());
            if (isset($_POST['images'])) {
                $oldOrder = $_POST['images'];
                $newOrder = [];
    
                foreach($oldOrder as $key => $value) {
                    $imageEntity = $entityManager->getRepository(Images::class)->findOneBy(['id' => $key]);
    
                    $imageEntity->setSortOrder($value);
                    $entityManager->persist($imageEntity);
                    $entityManager->flush();
                }
            }
            // dd($form, $form->getData()->getSpace(), $space);
            if ($form->getData()->getGalleries()->getImagePath()) {
                $this->handleNewImages($form, $request, $pictureService, $entityManager);
                
            }
            $form->getData()->getAdresse();

            $this->entityManager->persist($adresse);
            // $this->entityManager->flush();

            $space->setAdresse($adresse);
            $space->addAmenty($equipments);
            $userRepository->save($user);
            $spacesRepository->save($space);
            return $this->redirectToRoute('dashboard_host_annonces', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('dashboard_host/edit.html.twig', compact('space', 'form', 'galleries'));
    }

    private function handleNewImages($dataArray, $request, $pictureService, EntityManagerInterface $entityManager) {
        $folder = 'Archives/Spaces/' . $dataArray->getData()->getSpace()->getId() . '/Galleries';
        $initialOrder = count($entityManager->getRepository(Images::class)->findBy(['space' =>  $dataArray->getData()->getSpace()]));
        $dataImage = $request->files->all()['form_edit_space']['galleries']['imagePath'];
        $fichier = $pictureService->add($dataImage, $folder);
        
        $order = $initialOrder + 1;

        // Create a new Image object for each file
        $image = new Images();
        $image->setSpaces($dataArray->getData()->getSpace());
        $image->setImagePath($fichier);
        $image->setSortOrder($order);
        
        $entityManager->persist($image);
        $entityManager->flush();
    }
}
