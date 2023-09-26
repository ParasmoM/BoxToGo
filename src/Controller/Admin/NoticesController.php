<?php 

namespace App\Controller\Admin;

use Exception;
use App\Entity\Spaces;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class NoticesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    #[Route('/admin/notices', name: 'admin_notices')]
    public function notices(Request $request, SessionInterface $session): Response
    {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $flashMessage = null;
        // Récupération des messages flash "success"
        $messagesSuccess = $session->getFlashBag()->get('success', []);

        // Récupération des messages flash "error"
        $messagesError = $session->getFlashBag()->get('error', []);

        if ($messagesSuccess) {
            $flashMessage['success'] = $messagesSuccess[0];
        }
        if ($messagesError) {
            $flashMessage['error'] = $messagesError[0];
        }

        $idCategorie = $request->query->get('id');
        $reference = $request->query->get('ref');

        $repository = $this->em->getRepository(Spaces::class);

        if ($idCategorie) {
            $query = $repository->findBy(['type' => $idCategorie]);
        } elseif ($reference) {
            $query = $repository->findBy(['reference' => $reference]);
        } else {
            $query = $repository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/notices/notices.html.twig', [
            'dataSpaces' => $pagination,
            'flashMessage' => $flashMessage,
        ]);
    }

    #[Route('/admin/space/{id}', name: 'admin_spaces_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Spaces $space,
        SessionInterface $session,
        TranslatorInterface $trans,
    ): Response {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');
        
        if ($this->isCsrfTokenValid('delete'.$space->getId(), $request->request->get('_token'))) {
            try {
                $this->em->remove($space);
                $this->em->flush();
                
                $flashMessage = $this->trans->trans("Space successfully deleted.");
                $session>getFlashBag()->add('success', $flashMessage);
            } catch (Exception $e) {
                $flashMessage = $trans->trans("Unable to delete this space. It is linked to one or more reservations in the database.");
                $session->getFlashBag()->add('error', $flashMessage);
            }
        }

        return $this->redirectToRoute('admin_notices', [], Response::HTTP_SEE_OTHER);
    }
}