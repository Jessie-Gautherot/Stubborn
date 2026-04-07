<?php

namespace App\TestFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ======= 1 utilisateur client =======
        $userRepo = $manager->getRepository(User::class);

        $userEmail = 'testuser@example.com';
        $user = $userRepo->findOneBy(['email' => $userEmail]);
        if (!$user) {
            $user = new User();
            $user->setName('Test User');
            $user->setEmail($userEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setDeliveryAddress('123 Rue de Test, Paris, France');
            $manager->persist($user);
        }

        // ======= 3 produits =======
        $productsData = [
            ['name' => 'Blackbelt', 'price' => 29.90, 'image' => 'blackbelt.jpg', 'featured' => true],
            ['name' => 'BlueBelt', 'price' => 29.90, 'image' => 'bluebelt.jpg', 'featured' => false],
            ['name' => 'Street', 'price' => 34.50, 'image' => 'street.jpg', 'featured' => false],
        ];

        $productRepo = $manager->getRepository(Product::class);

        foreach ($productsData as $data) {
            if ($productRepo->findOneBy(['name' => $data['name']])) {
                continue;
            }

            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setImage($data['image']);
            $product->setFeatured($data['featured']);

            $product->setStockXS(5);
            $product->setStockS(5);
            $product->setStockM(5);
            $product->setStockL(5);
            $product->setStockXL(5);

            $manager->persist($product);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}