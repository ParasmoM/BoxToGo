<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\SpaceAmenities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/space/amenities')]
class SpaceAmenitiesController extends AbstractController
{
    #[Route('/{id}', name: 'app_space_amenities_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        SpaceAmenities $spaceAmenity, 
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        TranslatorInterface $trans,
    ): Response {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');
        
        if ($this->isCsrfTokenValid('delete'.$spaceAmenity->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($spaceAmenity);
                $entityManager->flush();

                $flashMessage = $trans->trans("The space type $spaceAmenity has been deleted.");
                $session->getFlashBag()->add('success', $flashMessage);
            } catch (Exception $e) {
                $flashMessage = $trans->trans("An error has occurred.");
                $session->getFlashBag()->add('error', $flashMessage);
            }
        }

        return $this->redirectToRoute('admin_analytics', [], Response::HTTP_SEE_OTHER);
    }
}
