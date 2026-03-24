<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Fixtures pour les produits de l'application Stubborn.
 *
 * Cette fixture :
 * - Crée une liste de produits avec prix, image, mise en avant et stock par taille
 * - Ajout d'une vérification pour éviter les doublons (findOneBy name)
 * - Compatible avec --append : ne purgera pas la table Product
 */
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste des produits avec leurs infos
        $productsData = [
            ['name' => 'Blackbelt', 'price' => 29.90, 'image' => 'blackbelt.jpg', 'featured' => true],
            ['name' => 'BlueBelt', 'price' => 29.90, 'image' => 'bluebelt.jpg', 'featured' => false],
            ['name' => 'Street', 'price' => 34.50, 'image' => 'street.jpg', 'featured' => false],
            ['name' => 'Pokeball', 'price' => 45.00, 'image' => 'pokeball.jpg', 'featured' => true],
            ['name' => 'PinkLady', 'price' => 29.90, 'image' => 'pinklady.jpg', 'featured' => false],
            ['name' => 'Snow', 'price' => 32.00, 'image' => 'snow.jpg', 'featured' => false],
            ['name' => 'Greyback', 'price' => 28.50, 'image' => 'greyback.jpg', 'featured' => false],
            ['name' => 'BlueCloud', 'price' => 45.00, 'image' => 'bluecloud.jpg', 'featured' => false],
            ['name' => 'BornInUsa', 'price' => 59.90, 'image' => 'borninusa.jpg', 'featured' => true],
            ['name' => 'GreenSchool', 'price' => 42.20, 'image' => 'greenschool.jpg', 'featured' => false],
        ];

        // Récupération du repository pour vérifier les doublons
        $productRepo = $manager->getRepository(Product::class);

        foreach ($productsData as $data) {
            // Vérifie si le produit existe déjà en base (anti-doublon)
            if ($productRepo->findOneBy(['name' => $data['name']])) {
                continue; // skip si déjà présent
            }

            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setImage($data['image']);
            $product->setFeatured($data['featured']);

            // Stock : 5 exemplaires par taille par défaut
            $product->setStockXS(5);
            $product->setStockS(5);
            $product->setStockM(5);
            $product->setStockL(5);
            $product->setStockXL(5);

            $manager->persist($product);
        }

        // Envoi de tous les produits en base
        $manager->flush();
    }
}