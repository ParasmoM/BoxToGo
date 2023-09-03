<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Payments;
use App\Entity\Reservations;
use App\Form\PaymentFormType;
use App\Services\StripeManager;
use App\Services\StripeServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $id = $request->query->get('id');
        $resa = $this->enttityManager->getRepository(Reservations::class)->find($id);
        
        $session = $request->getSession();
        $session->set('resa_id', $id);
        
        $checkout = $this->stripeServices->sessionDetailsSession($resa);
        return $this->redirect($checkout->url);
    }

    #[Route('/checkout/success', name: 'app_checkout_success   ')]
    public function success(
        Request $request,
    ): Response {
        $session = $request->getSession();
        $resa_id = $session->get('resa_id');
        $resa = $this->enttityManager->getRepository(Reservations::class)->find($resa_id);

        $sessionId = $request->query->get('session_id');
        $customer = $this->stripeServices->retrieveSessionDetails($sessionId);

        $payment = new Payments();
        $payment->setName($customer['name']);
        $payment->setEmail($customer['email']);
        $payment->setAmount($customer['price']);
        $payment->setStripeStatus($customer['status']);
        $payment->setMethod($customer['method']);
        $payment->setStripeChargeId($customer['stripeId']);
        $payment->setUser($resa->getUser());
        $payment->setReservation($resa);

        $this->enttityManager->persist($payment);
        $resa->setPayment($payment);
        $this->enttityManager->flush();

        return $this->redirectToRoute('public_home');
    }

    #[Route('/checkout/cancel', name: 'app_checkout_cancel')]
    public function cancel(
        Request $request,
    ): Response {
        dd('cancel', $request);
    }
}
