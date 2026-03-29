<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    public function __construct(private CartService $cartService)
    {
    }

    /**
     * Afficher le panier
     */
    #[Route('/cart', name: 'cart_show')]
    public function show(): Response
    {

    return $this->render('cart/show.html.twig', [
        'cart' => $this->cartService->getDetailedCart(),
        'total' => $this->cartService->getTotal(),
    ]);
    }

    /**
 * Ajouter un produit au panier
 */
    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
    // Récupérer directement tous les champs POST
    $data = $request->request->all('add_to_cart');

    $productId = isset($data['productId']) ? (int) $data['productId'] : 0;
    $size      = isset($data['size']) ? (string) $data['size'] : '';
    $quantity  = isset($data['quantity']) ? (int) $data['quantity'] : 1;

    // Vérifie la validité des données
    if (!$productId || !$size || $quantity < 1) {
        $this->addFlash('error', 'Données invalides.');
        return $this->redirectToRoute('product_list');
    }
    // Ajoute le produit au panier
    $this->cartService->addProduct($productId, $size, $quantity);
    // message de confirmation
    $this->addFlash('success', 'Produit ajouté au panier.');
    return $this->redirectToRoute('cart_show');
}
    

    /**
     * Supprimer un produit
     */
    #[Route('/cart/remove', name: 'cart_remove', methods: ['POST'])]
    public function remove(Request $request): Response
    {
        $productId = (int) $request->request->get('productId');
        $size = (string) $request->request->get('size');

        $this->cartService->removeProduct($productId, $size);

        $this->addFlash('success', 'Produit retiré du panier.');

        return $this->redirectToRoute('cart_show');
    }

    /**
     * Vider le panier
     */
    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $this->cartService->clearCart();

        $this->addFlash('success', 'Panier vidé.');

        return $this->redirectToRoute('cart_show');
    }

    /**
     * Checkout (Stripe plus tard)
     */
    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(): Response
    {
        // Ici brancher Stripe plus tard
        $this->addFlash('success', 'Paiement simulé (à connecter avec Stripe).');

        return $this->redirectToRoute('cart_show');
    }
}