<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\User;
use App\Entity\Reviews;
use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
        private TranslatorInterface $trans,
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->trans = $trans;
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function users(Request $request, SessionInterface $session): Response
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

        $statusUser = $request->query->get('status');

        $nameUser = $request->query->get('name');
        
        $repository = $this->em->getRepository(User::class);

        if ($statusUser) {
            $query = $repository->findBy(['status' => $statusUser]);
        } elseif ($nameUser) {
            $verif = $this->verifySearch($nameUser);

            if ($verif['isSingleWord']) {
                $firstName = $repository->findBy(['givenName' => $nameUser]);
                $lastName = $repository->findBy(['familyName' => $nameUser]);

                if (!empty($firstName)) {
                    $query[] = $firstName;
                }
                
                if (!empty($lastName)) {
                    $query[] = $lastName;
                }

            } elseif ($verif['isMultipleWords']) {
                $wordsArray = explode(" ", $nameUser);
                if (count($wordsArray) > 2) {
                    $query = $repository->findAll();
                } else {
                    $foundByGivenAndFamilyName = $repository->findBy(['givenName' => $wordsArray[0], 'familyName' => $wordsArray[1]]);
                    $foundByFamilyAndGivenName = $repository->findBy(['givenName' => $wordsArray[1], 'familyName' => $wordsArray[0]]);
                    
                    if (!empty($foundByGivenAndFamilyName)) {
                        $query[] = $foundByGivenAndFamilyName;
                    }
                    
                    if (!empty($foundByFamilyAndGivenName)) {
                        $query[] = $foundByFamilyAndGivenName;
                    }
                }
            } 
            
            if (empty($query)) {
                $query = $repository->findAll();
            }

        } else {
            $query = $repository->findAll();
        }
        
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );
        
        return $this->render('admin/users/users.html.twig', [
            'dataUsers' => $pagination,
            'flashMessage' => $flashMessage
        ]);
    }

    #[Route('/admin/renter/profile/{id}', name: 'admin_users_profile')]
    public function renterProfile(
        User $user, 
        Request $request,
    ): Response {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        $repoReview = $this->em->getRepository(Reviews::class);
        
        $reviews = $repoReview->findBy(['user' => $user->getId()]);

        $dataReview['pagination'] = $this->paginator->paginate(
            $reviews,
            $request->query->getInt('page1', 1),
            1,
            [
                'pageParameterName' => 'page1'
            ]
        );

        $dataReview['count'] = count($reviews);

        $repoResa = $this->em->getRepository(Reservations::class);

        $resa = $repoResa->findBy(['user' => $user->getId()]);

        $dataResa['pagination'] = $this->paginator->paginate(
            $resa,
            $request->query->getInt('page2', 1),
            1,
            [
                'pageParameterName' => 'page2'
            ]
        );

        $dataResa['count'] = count($resa);
        
        return $this->render('admin/users/_renter_profile.html.twig', [
            'data' => $user,
            'dataReview' => $dataReview,
            'dataResa' => $dataResa,
        ]);
    }

    #[Route('/admin/user/delete/{id}', name: 'admin_users_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        User $user, 
        SessionInterface $session,
    ): Response {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            try {
                $this->em->remove($user);
                $this->em->flush();
                
                $flashMessage = $this->trans->trans("User successfully deleted.");
                $session>getFlashBag()->add('success', $flashMessage);
            } catch (Exception $e) {
                $flashMessage = $this->trans->trans("Unable to delete this user. They are linked to one or more spaces in the database.");
                $session->getFlashBag()->add('error', $flashMessage);
            }
        }

        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('admin/{id}', name: 'admin_role_delete', methods: ['POST'])]
    public function deleteRole(
        Request $request, 
        User $user, 
        SessionInterface $session,
    ): Response {
        $currentUser = $this->getUser();
        if (!in_array('ROLE_ADMIN', $currentUser->getRoles())) return $this->redirectToRoute('public_home');
        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            try {
                $arrayRoles = $user->getRoles();

                $key = array_search('ROLE_ADMIN', $arrayRoles);
    
                if ($key !== false) {
                    unset($arrayRoles[$key]);
                    $user->setRoles($arrayRoles); 
                    $this->em->persist($user);
                    $this->em->flush();
                }

                $flashMessage = $this->trans->trans("User successfully deleted.");
                $session->getFlashBag()->add('success', $flashMessage);
            } catch (Exception $e) {
                $flashMessage = $this->trans->trans("Unable to delete this user. They are likely linked to other data.");
                $session->getFlashBag()->add('error', $flashMessage);
            }
        }

        return $this->redirectToRoute('admin_analytics', [], Response::HTTP_SEE_OTHER);
    }
        
    /**
     * Vérifie la valeur de la barre de recherche et retourne un tableau associatif 
     * avec des indicateurs comme "isNumber", "isSingleWord" et "isMultipleWords".
     *
     * @param string $searchBar La valeur de la barre de recherche à vérifier.
     * @return array Un tableau associatif contenant des indicateurs.
     */
    public function verifySearch($searchBar)
    {
        $search = $searchBar;
    
        $result = [];
    
        // Utilise preg_split pour séparer la chaîne de recherche en mots individuels,
        // en utilisant des espaces comme délimiteurs
        $words = preg_split('/\s+/', $search);
    
        // Initialise les compteurs pour les valeurs numériques et les chaînes de caractères
        $numericCount = 0;
        $stringCount = 0;
    
        // Parcourt chaque mot pour compter les valeurs numériques et les chaînes de caractères
        foreach ($words as $word) {
            if (is_numeric($word)) {
                $numericCount++;
            } else {
                $stringCount++;
            }
        }
    
        // Vérifie si tous les mots sont numériques
        $result['isNumber'] = $numericCount > 0 && $stringCount === 0;
    
        // Vérifie si la recherche contient un seul mot
        $result['isSingleWord'] = count($words) === 1;
    
        // Vérifie si la recherche contient plusieurs mots qui ne sont pas numériques
        $result['isMultipleWords'] = $stringCount > 1;
    
        // Retourne le résultat sous forme de tableau associatif
        return $result;
    }   
}