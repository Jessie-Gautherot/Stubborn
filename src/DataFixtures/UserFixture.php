<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixtures pour les utilisateurs de l'application Stubborn.
 *
 * Ce fichier crée :
 * - Deux utilisateurs clients (Alice et Bob) avec rôle ROLE_USER
 * - Deux administrateurs (Admin1 et Admin2) avec rôle ROLE_ADMIN
 *
 * Les mots de passe sont en clair ici pour la fixture, mais seront
 * hashés automatiquement par Symfony avant insertion dans la base.
 * 
 * Cette fixture est uniquement destinée à l'environnement local ou de test.
 *
 * MODIFICATIONS :
 * - Ajout d'une vérification pour éviter les doublons (findOneBy email)
 * - Compatible avec --append : ne purge pas les utilisateurs existants
 */
class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Injection du service de hashage de mot de passe.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Charge les utilisateurs dans la base de données.
     */
    public function load(ObjectManager $manager): void
    {
        // Récupération du repository pour vérifier les doublons
        $userRepo = $manager->getRepository(User::class);

        // ------------------------
        // Utilisateur client 1 : Alice
        // ------------------------
        if (!$userRepo->findOneBy(['email' => 'alice@example.com'])) { // <-- vérifie si Alice existe déjà
            $alice = new User();
            $alice->setName('Alice');
            $alice->setEmail('alice@example.com');
            $alice->setRoles(['ROLE_USER']);
            $alice->setPassword($this->passwordHasher->hashPassword($alice, 'password123'));
            $alice->setDeliveryAddress('10 Rue de la Paix, Paris, France');
            $manager->persist($alice);
        }

        // ------------------------
        // Utilisateur client 2 : Bob
        // ------------------------
        if (!$userRepo->findOneBy(['email' => 'bob@example.com'])) { // <-- vérifie si Bob existe déjà
            $bob = new User();
            $bob->setName('Bob');
            $bob->setEmail('bob@example.com');
            $bob->setRoles(['ROLE_USER']);
            $bob->setPassword($this->passwordHasher->hashPassword($bob, 'password456'));
            $bob->setDeliveryAddress('15 Avenue Victor Hugo, Lyon, France');
            $manager->persist($bob);
        }

        // ------------------------
        // Administrateur 1 : Admin1
        // ------------------------
        if (!$userRepo->findOneBy(['email' => 'admin1@example.com'])) { // <-- vérifie si Admin1 existe déjà
            $admin1 = new User();
            $admin1->setName('Admin1');
            $admin1->setEmail('admin1@example.com');
            $admin1->setRoles(['ROLE_ADMIN']);
            $admin1->setPassword($this->passwordHasher->hashPassword($admin1, 'admin123'));
            $manager->persist($admin1);
        }

        // ------------------------
        // Administrateur 2 : Admin2
        // ------------------------
        if (!$userRepo->findOneBy(['email' => 'admin2@example.com'])) { // <-- vérifie si Admin2 existe déjà
            $admin2 = new User();
            $admin2->setName('Admin2');
            $admin2->setEmail('admin2@example.com');
            $admin2->setRoles(['ROLE_ADMIN']);
            $admin2->setPassword($this->passwordHasher->hashPassword($admin2, 'admin456'));
            $manager->persist($admin2);
        }

        // ------------------------
        // Envoi des données en base
        // ------------------------
        $manager->flush(); // <-- flush final unique pour tous les persist
    }
}
