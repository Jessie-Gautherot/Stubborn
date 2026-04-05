<?php

namespace App\Tests;

use App\Service\StripeService;
use PHPUnit\Framework\TestCase;
use Stripe\PaymentIntent;
use Stripe\Service\PaymentIntentService;
use Stripe\StripeClient;

class StripeServiceTest extends TestCase
{
    public function testCreatePaymentIntent(): void
    {
        /**
         * PaymentIntent simulé
         */
        $paymentIntent = new PaymentIntent();
        $paymentIntent->amount = 5980;
        $paymentIntent->currency = 'eur';

        /**
         * Mock du service Stripe interne
         */
        $paymentIntentService = $this->createMock(PaymentIntentService::class);
        $paymentIntentService
            ->method('create')
            ->willReturn($paymentIntent);

        /**
         * Création d'un faux StripeClient SANS propriété dynamique
         */
        $stripeClient = new class($paymentIntentService) extends StripeClient {
            public function __construct(private $paymentIntentService)
            {
                // on passe une clé fake
                parent::__construct('sk_test_fake');
            }

            public function __get($name)
            {
                if ($name === 'paymentIntents') {
                    return $this->paymentIntentService;
                }

                return null;
            }
        };

        /**
         * Service à tester
         */
        $stripeService = new StripeService($stripeClient);

        /**
         * Panier simulé
         */
        $product = new class {
            public function getPrice(): float
            {
                return 29.90;
            }
        };

        $cart = [
            [
                'product' => $product,
                'quantity' => 2
            ]
        ];

        /**
         * Exécution
         */
        $result = $stripeService->createPaymentIntent($cart);

        /**
         * Assertions
         */
        $this->assertInstanceOf(PaymentIntent::class, $result);
        $this->assertEquals(5980, $result->amount);
        $this->assertEquals('eur', $result->currency);
    }
}