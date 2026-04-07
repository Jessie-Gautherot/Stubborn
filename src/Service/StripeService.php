<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeService
{
    public function __construct(private StripeClient $stripeClient)
    {
        // S'assurer que Stripe est en mode test
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    }

    /**
     * Crée un PaymentIntent Stripe à partir du panier
     */
    public function createPaymentIntent(array $cart): PaymentIntent
    {
        $amount = 0;

        foreach ($cart as $item) {
            $product = $item['product'];
            $amount += (int) ($product->getPrice() * 100) * $item['quantity'];
        }

        return $this->stripeClient->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
        ]);
    }
}