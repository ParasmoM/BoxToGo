<?php
namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Entity\Reservations;
use Stripe\Checkout\Session;

class StripeManager 
{
    private $apiSecretKey;
    private $apiKey;

    public function __construct () 
    {
        if ($_ENV['APP_ENV'] == 'dev') {
            $this->apiSecretKey = $_ENV['STRIPE_SECRET_KEY_TEST'];

            $this->apiKey = Stripe::setAPIKey($this->apiSecretKey);
        }
    }

    public function createPaymentIntent(
        Reservations $resa
    ): PaymentIntent {
        $this->apiKey;

        return PaymentIntent::create([
            'amount' => $resa->getPrice() * 100,  
            'currency' => 'EUR',
            'payment_method_types' => ['card'],
        ]);
    }
    
    public function startPayement(
        $amount, 
        $currency,
        $description,
        array $stripeParameters
    ) {
        $this->apiKey;
        $payment_intent = null; 

        if (isset($stripeParameters['stripeIntentId'])) {
            $payment_intent = PaymentIntent::retrieve($stripeParameters['stripeIntentId']);
        }

        if ($stripeParameters['stripeIntentId'] === 'succeeded') {
            // todo
        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    public function stripe(array $stripeParameters, Reservations $resa) {
        $language = $resa->getSpace()->getHost()->getLanguage();
        $title = $resa->getSpace()->getContent()->getTitleBasedOnLanguage($language);

        return $this->startPayement(
            $resa->getPrice() * 100,
            'EUR',
            $title, 
            $stripeParameters
        );
    }
}