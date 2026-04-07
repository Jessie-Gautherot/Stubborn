<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
     public function home(ProductRepository $productRepository): Response
    {
        // Récupère les produits mis en avant depuis la base
        $featuredProducts = $productRepository->findFeatured();

        // Passe la variable à Twig
        return $this->render('home.html.twig', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}