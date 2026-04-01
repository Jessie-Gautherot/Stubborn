<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProductRepository;

class CartService
{
    private $session;
    private ProductRepository $productRepository;

    private const CART_KEY = 'cart';

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;

        if (!$this->session->has(self::CART_KEY)) {
            $this->session->set(self::CART_KEY, []);
        }
    }


    /**
     * Ajouter un produit au panier
     */
    public function addProduct(int $productId, string $size, int $quantity = 1): void
    {
        $cart = $this->getCart();

        foreach ($cart as &$item) {
            if ($item['productId'] === $productId && $item['size'] === $size) {
                $item['quantity'] += $quantity;
                $this->session->set(self::CART_KEY, $cart);
                return;
            }
        }

        $cart[] = [
            'productId' => $productId,
            'size' => $size,
            'quantity' => $quantity,
        ];

        $this->session->set(self::CART_KEY, $cart);
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProduct(int $productId, string $size): void
    {
        $cart = $this->getCart();

        $cart = array_filter($cart, fn($item) =>
            !($item['productId'] === $productId && $item['size'] === $size)
        );

        $this->session->set(self::CART_KEY, array_values($cart));
    }

    /**
     * Vider le panier
     */
    public function clearCart(): void
    {
        $this->session->set(self::CART_KEY, []);
    }

    /**
     * Récupérer le panier
     *
     * @return array<int, array{productId:int, size:string, quantity:int}>
     */
    public function getCart(): array
    {
        return $this->session->get(self::CART_KEY, []);
    }

    /**
     * Récupérer les produits complets du panier
     * (utile pour Twig)
     */
    public function getDetailedCart(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $item) {
            $product = $this->productRepository->find($item['productId']);

            if ($product) {
                $detailedCart[] = [
                    'image' => $product->getImage(),
                    'product' => $product,
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'total' => $product->getPrice() * $item['quantity'],
                ];
            }
        }

        return $detailedCart;
    }

    /**
     * Calculer le total du panier
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $product = $this->productRepository->find($item['productId']);

            if ($product) {
                $total += $product->getPrice() * $item['quantity'];
            }
        }

        return $total;
    }
}