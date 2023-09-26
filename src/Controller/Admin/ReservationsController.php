<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    #[Route('/admin/resa', name: 'admin_resa')]
    public function resa(Request $request): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $reference = $request->query->get('ref');

        $repository = $this->em->getRepository(Reservations::class);

        if ($reference) {
            $query = $repository->findBy(['reference' => $reference]);
        } else {
            $query = $repository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/reservations/reservation.html.twig', [
            'dataResa' => $pagination,
        ]);
    }

    #[Route('/admin/resa/detail/{id}', name: 'admin_resa_detail')]
    public function resaDetail(Reservations $resa): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');
        
        $repository = $this->em->getRepository(Reservations::class);

        return $this->render('admin/reservations/_reservation_detail.html.twig', [
            'data' => $repository->findOneBy(['id' => $resa->getId()]),
        ]);
    }
}