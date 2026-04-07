<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

// Déclaration de l'entité User, liée au repository UserRepository
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant unique
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nom de l'utilisateur
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Email unique : utilisé comme identifiant de connexion pour Symfony Security
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Rôles de l'utilisateur : contrôle l'accès aux pages (ex : ROLE_ADMIN pour le back-office)
    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    // Mot de passe encodé
    #[ORM\Column]
    private ?string $password = null;

    // Adresse de livraison optionnelle (nullable, validation gérée dans le formulaire)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryAddress = null;

    // Vérification de l'email
    #[ORM\Column]
    private bool $isVerified = false;

    // GETTERS / SETTERS

    /**
     * Retourne l'identifiant interne de l'utilisateur.
     * Utilisé pour les relations en base de données.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de l'utilisateur pour affichage ou formulaire.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom de l'utilisateur depuis un formulaire ou en back-office.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Retourne l'email de l'utilisateur (identifiant pour la connexion).
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'email de l'utilisateur (inscription ou modification profil).
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Méthode obligatoire de UserInterface.
     * Renvoie l'identifiant unique de l'utilisateur pour Symfony Security.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email; 
    }

    /**
     * Retourne la liste des rôles de l'utilisateur.
     * Symfony Security utilise ces rôles pour contrôler l'accès aux pages.
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    /**
     * Définit les rôles de l'utilisateur (ex : ['ROLE_USER', 'ROLE_ADMIN']).
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Retourne le mot de passe encodé pour l'authentification.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * Définit le mot de passe encodé depuis le formulaire.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Retourne l'adresse de livraison de l'utilisateur.
     */
    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    /**
     * Définit l'adresse de livraison de l'utilisateur.
     */
    public function setDeliveryAddress(?string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * Indique si l'utilisateur a confirmé son email.
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Définit si l'utilisateur a confirmé son email.
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    /**
     * Méthode obligatoire de UserInterface.
     * Permet d'effacer des données sensibles temporaires si nécessaire.
     */
    public function eraseCredentials(): void
    {}
}
