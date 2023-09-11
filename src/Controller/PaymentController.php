<?php

namespace App\Controller;

use App\Entity\Payments;
use App\Entity\Reservations;
use App\Services\StripeServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\UserNotAuthenticatedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentController extends AbstractController
{
    public function __construct (
        private EntityManagerInterface $enttityManager,
        private StripeServices $stripeServices
    ) {
        $this->enttityManager = $enttityManager;
        $this->stripeServices = $stripeServices;
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(
        Request $request,
        ParameterBagInterface $params,
    ): Response {
        try {
            if (!$this->getUser()) {
                throw new UserNotAuthenticatedException("L'utilisateur doit être connecté pour accéder à la page.");
            }
        } catch (UserNotAuthenticatedException $e) {
            $request->getSession()->set('errorMessage', $e->getMessage());
            return $this->redirectToRoute('app_error_403');
        }

        $id = $request->query->get('id');
        $resa = $this->enttityManager->getRepository(Reservations::class)->find($id);
        
        // dd($resa);
        $session = $request->getSession();
        $session->set('resa_id', $id);
        
        $checkout = $this->stripeServices->sessionDetailsSession($resa);
        return $this->redirect($checkout->url);
    }

    #[Route('/checkout/success', name: 'app_checkout_success   ')]
    public function success(
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

        $session = $request->getSession();
        $resa_id = $session->get('resa_id');
        $resa = $this->enttityManager->getRepository(Reservations::class)->find($resa_id);

        $sessionId = $request->query->get('session_id');
        $customer = $this->stripeServices->retrieveSessionDetails($sessionId);
        // dd($customer);
        $payment = new Payments();
        $payment->setName($customer['name']);
        $payment->setEmail($customer['email']);
        $payment->setPrice($customer['price']);
        $payment->setStripeStatus($customer['status']);
        $payment->setMethod($customer['method']);
        $payment->setStripeId($customer['stripeId']);
        $payment->setStripeToken($customer['intent']);
        $payment->setUser($resa->getUser());
        $payment->setReservations($resa);

        $this->enttityManager->persist($payment);

        $resa->setPayment($payment);
        $resa->setStatus('busy');

        $space = $resa->getSpace();
        $space->setStatus('busy');
        $space->setRentedByUser($this->getUser());

        $this->getUser()->addRenter($space);

        $this->enttityManager->flush();

        return $this->redirectToRoute('public_home');
    }

    #[Route('/checkout/cancel', name: 'app_checkout_cancel')]
    public function cancel(
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
        
        dd('cancel', $request);
    }
}
