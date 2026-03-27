<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddToCartType;

class ProductController extends AbstractController
{
    /**
     * Liste tous les produits et permet de filtrer par fourchette de prix
     * Route : /products
     */
    #[Route('/products', name: 'product_list')]
    public function list(Request $request, ProductRepository $productRepository): Response
    {
        // Récupère la fourchette de prix passée en query string (ex: /products?price_range=10-29)
        $priceRange = $request->query->get('price_range'); 
        $products = [];

        if ($priceRange) {
            // On s'assure que le format est correct (doit contenir un '-')
            if (strpos($priceRange, '-') !== false) {
                // Découpe la fourchette en min et max
                [$min, $max] = explode('-', $priceRange);
                // Appelle le repository pour récupérer les produits dans cette fourchette de prix
                $products = $productRepository->findByPriceRange((float)$min, (float)$max);
            } else {
                // Si le format est invalide, on retourne tous les produits
                $products = $productRepository->findAllProducts();
            }
        } else {
            // Si aucune fourchette n'est passée, on retourne tous les produits
            $products = $productRepository->findAllProducts();
        }

        // Envoie les données à la vue Twig pour affichage
        return $this->render('product/list.html.twig', [
            'products' => $products,        // Liste des produits filtrés ou complets
            'selected_range' => $priceRange // Pour pré-sélectionner la fourchette dans la vue
        ]);
    }

    /**
     * Affiche le détail d’un produit
     * Route : /product/{id}
     */
    #[Route('/product/{id}', name: 'product_show')]
    public function show(int $id, Request $request, ProductRepository $productRepository): Response
    {
        // Récupère le produit via le repository
        $product = $productRepository->findProductById($id);

        // Si le produit n'existe pas, on renvoie une erreur 404
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        // Préparer le stock pour chaque taille
        // Ceci permet à Twig de désactiver ou cacher les tailles indisponibles
        $stock = [
            'XS' => $product->getStockXS(),
            'S'  => $product->getStockS(),
            'M'  => $product->getStockM(),
            'L'  => $product->getStockL(),
            'XL' => $product->getStockXL(),
        ];

        // Création du formulaire
    $form = $this->createForm(AddToCartType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $size = $data['size'];

        // Ici on utilisera ton panier session (plus tard)
        // dump($size);

        return $this->redirectToRoute('product_list');
    }

        // Envoie le produit et le stock à la vue Twig pour affichage
        return $this->render('product/show.html.twig', [
            'product' => $product, // Objet Product complet
            'stock' => $stock,     // Tableau associatif taille => stock
            'form' => $form->createView(),
        ]);
    }
}


//logique métier (décrément du stock) dans un service