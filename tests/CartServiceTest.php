<?php

namespace App\Tests;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends TestCase
{
    private CartService $cartService;

    protected function setUp(): void
    {
        /**
         * Création d'une session simulée
         * Permet de tester le panier sans dépendre d'une session réelle ou d'un navigateur.
         */
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        /**
         * Création de deux produits fictifs qui serviront pour le calcul du panier et du total.
         */
        $product1 = new Product();
        $product1->setName('Produit test 1');
        $product1->setPrice(10.00);
        $product1->setImage('test1.jpg');
        $product1->setFeatured(false);
        $product1->setStockXS(5);
        $product1->setStockS(5);
        $product1->setStockM(5);
        $product1->setStockL(5);
        $product1->setStockXL(5);

        $product2 = new Product();
        $product2->setName('Produit test 2');
        $product2->setPrice(15.00);
        $product2->setImage('test2.jpg');
        $product2->setFeatured(false);
        $product2->setStockXS(5);
        $product2->setStockS(5);
        $product2->setStockM(5);
        $product2->setStockL(5);
        $product2->setStockXL(5);

        /**
         * Mock du ProductRepository
         * Quand on appelle find(1) ou find(2), on retourne les produits fictifs correspondants.
         */
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository
            ->method('find')
            ->willReturnMap([
                [1, $product1],
                [2, $product2],
            ]);

        /**
         * Injection dans le service CartService
         */
        $this->cartService = new CartService($requestStack, $productRepository);
    }

    public function testCartWorkflow(): void
    {
        // ======= AJOUT DU PREMIER PRODUIT =======
        $this->cartService->addProduct(1, 'M', 2);

        $cart = $this->cartService->getCart();
        $this->assertCount(1, $cart, "Le panier doit contenir 1 produit après ajout du premier.");
        $this->assertEquals(2, $cart[0]['quantity'], "La quantité du produit 1 doit être correcte.");

        $detailedCart = $this->cartService->getDetailedCart();
        $this->assertEquals(20.00, $detailedCart[0]['total'], "Le total du produit 1 doit être quantity x prix.");

        $total = $this->cartService->getTotal();
        $this->assertEquals(20.00, $total, "Le total global doit correspondre au premier produit.");

        // ======= AJOUT DU SECOND PRODUIT =======
        $this->cartService->addProduct(2, 'S', 1);

        $cart = $this->cartService->getCart();
        $this->assertCount(2, $cart, "Le panier doit contenir 2 produits après ajout du second.");

        // Vérification du total global après ajout du second produit
        $totalAfterSecond = $this->cartService->getTotal();
        $this->assertEquals(20.00 + 15.00, $totalAfterSecond, "Le total global doit se mettre à jour après ajout du second produit.");

        // ======= SUPPRESSION DU PREMIER PRODUIT =======
        $this->cartService->removeProduct(1, 'M');
        $cartAfterRemoval = $this->cartService->getCart();
        $this->assertCount(1, $cartAfterRemoval, "Le panier doit contenir 1 produit après suppression du premier.");
        $this->assertEquals(2, $cartAfterRemoval[0]['productId'], "Le produit restant doit être le second ajouté.");

        $totalAfterRemoval = $this->cartService->getTotal();
        $this->assertEquals(15.00, $totalAfterRemoval, "Le total doit se mettre à jour après suppression du produit.");

        // ======= VIDAGE DU PANIER =======
        $this->cartService->clearCart();
        $cartAfterClear = $this->cartService->getCart();
        $this->assertEmpty($cartAfterClear, "Le panier doit être vide après avoir été vidé.");

        $totalAfterClear = $this->cartService->getTotal();
        $this->assertEquals(0.00, $totalAfterClear, "Le total doit être 0 après vidage du panier.");
    }
}
