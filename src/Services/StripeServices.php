<?php
namespace App\Services;

use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Entity\Reservations;

class StripeServices
{ 
    private $stripeClient;
    private $redirectDomain;

    public function __construct()
    {
        $this->stripeClient = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $this->redirectDomain = 'https://127.0.0.1:8000';
    }

    public function sessionDetailsSession (
        Reservations $resa
    ) {
        $hostLanguage = $resa->getSpace()->getOwnedByUser()->getLanguage();
        $spaceTitle = $resa->getSpace()->getContent()->getTitleBasedOnLanguage($hostLanguage);
        
        return $this->stripeClient->checkout->sessions->create(
            [
                'payment_method_types' => ['card'],
                'line_items' =>[[
                    'price_data'=> [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'un titre',
                        ],
                        'unit_amount' => $resa->getPrice() * 100,
                    ],
                    'quantity' => 1,
                ]],
                'shipping_address_collection' => [
                    'allowed_countries' => ['BE'],
                ],
                'mode' => 'payment',
                'success_url' => $this->redirectDomain . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $this->redirectDomain . '/checkout/cancel?session_id={CHECKOUT_SESSION_ID}',
            ]
        );
    }

    public function retrieveSessionDetails($sessionId) 
    {
        $sessionDetails = $this->stripeClient->checkout->sessions->retrieve(
            $sessionId,
            []
        );

        return [
            'all' => $sessionDetails,
            'name' => $sessionDetails['customer_details']['name'],
            'email' => $sessionDetails['customer_details']['email'],
            'price' => $sessionDetails['amount_total'],
            'status' => $sessionDetails['payment_status'],
            'method' => $sessionDetails['payment_method_types'][0],
            'stripeId' => $sessionDetails['id'],
            'intent' => $sessionDetails['payment_intent'],
            'country' => $sessionDetails['customer_details']['address']['country'],
            'city' => $sessionDetails['customer_details']['address']['city'],
            'zip' => $sessionDetails['customer_details']['address']['postal_code'],
            'line' => $sessionDetails['customer_details']['address']['line1'],
        ];
    }
}