<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
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
 * - Ajout du role user au Admin pour pouvoir tester
 */
class UserFixture extends Fixture implements FixtureGroupInterface
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

     
        // Utilisateur client 1 : Alice
        
        if (!$userRepo->findOneBy(['email' => 'alice@example.com'])) { // <-- vérifie si Alice existe déjà
            $alice = new User();
            $alice->setName('Alice');
            $alice->setEmail('alice@example.com');
            $alice->setRoles(['ROLE_USER']);
            $alice->setPassword($this->passwordHasher->hashPassword($alice, 'password123'));
            $alice->setDeliveryAddress('10 Rue de la Paix, Paris, France');
            $manager->persist($alice);
        }

       
        // Utilisateur client 2 : Bob
       
        if (!$userRepo->findOneBy(['email' => 'bob@example.com'])) { // <-- vérifie si Bob existe déjà
            $bob = new User();
            $bob->setName('Bob');
            $bob->setEmail('bob@example.com');
            $bob->setRoles(['ROLE_USER']);
            $bob->setPassword($this->passwordHasher->hashPassword($bob, 'password456'));
            $bob->setDeliveryAddress('15 Avenue Victor Hugo, Lyon, France');
            $manager->persist($bob);
        }

        // Administrateurs
        
        $adminsData = [
            ['name' => 'Admin1', 'email' => 'admin1@example.com', 'password' => 'admin123'],
            ['name' => 'Admin2', 'email' => 'admin2@example.com', 'password' => 'admin456'],
        ];

        foreach ($adminsData as $data) {
            $admin = $userRepo->findOneBy(['email' => $data['email']]);
            if (!$admin) {
                $admin = new User();
                $admin->setName($data['name']);
                $admin->setEmail($data['email']);
                $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
                $admin->setPassword($this->passwordHasher->hashPassword($admin, $data['password']));
                $manager->persist($admin);
            } else {
                // Admin existant → assure qu'il a ROLE_USER en plus
                $roles = $admin->getRoles();
                if (!in_array('ROLE_USER', $roles)) {
                    $roles[] = 'ROLE_USER';
                    $admin->setRoles($roles);
                    $manager->persist($admin);
                }
            }
        }

        // Envoi des données en base
        $manager->flush(); 
    }

    public static function getGroups(): array
    {
    return ['dev'];
    }
}
