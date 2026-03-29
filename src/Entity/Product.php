<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;

/**
 * Entité Product représentant un sweat-shirt dans la boutique
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /** 
     * ID
     * Clé primaire auto-incrémentée
     */
    #[ORM\Id] 
    #[ORM\GeneratedValue] 
    #[ORM\Column(type:"integer")] 
    private $id;

    /** 
     * Nom du produit
     */
    #[ORM\Column(type:"string", length:255)] 
    private $name;

    /** 
     * Prix du produit
     */
    #[ORM\Column(type:"float")] 
    private $price;

    /** 
     * Image du produit
     */
    #[ORM\Column(type:"string", length:255)] 
    private $image;

    /** 
     * Mise en avant ? (booléen)
     */
    #[ORM\Column(type:"boolean")]
    private $featured = false;

    /** 
     * Stock par taille
     */
    #[ORM\Column(type:"integer")]
    private $stockXS = 0;

    #[ORM\Column(type:"integer")]
    private $stockS = 0;

    #[ORM\Column(type:"integer")]
    private $stockM = 0;

    #[ORM\Column(type:"integer")]
    private $stockL = 0;

    #[ORM\Column(type:"integer")]
    private $stockXL = 0;

    // GETTERS ET SETTERS

    /** 
     * ID
     * Retourne l'ID du produit
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** 
     * NAME
     * Getter pour lire le nom du produit
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** 
     * NAME
     * Setter pour modifier le nom du produit
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** 
     * PRICE
     * Getter pour lire le prix
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /** 
     * PRICE
     * Setter pour modifier le prix
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /** 
     * IMAGE
     * Getter pour récupérer l'image du produit
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /** 
     * IMAGE
     * Setter pour modifier l'image
     */
    public function setImage(?string $image): self
{
    $this->image = $image;
    return $this;
}

    /** 
     * FEATURED
     * Getter pour savoir si le produit est mis en avant
     */
    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    /** 
     * FEATURED
     * Setter pour modifier si le produit est mis en avant
     */
    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;
        return $this;
    }

    /** 
     * STOCK XS
     * Retourne le stock actuel pour la taille XS
     */
    public function getStockXS(): int { return $this->stockXS; }

    /** 
     * STOCK XS
     * Met à jour le stock XS
     */
    public function setStockXS(int $stockXS): self { $this->stockXS = $stockXS; return $this; }

    /** 
     * STOCK S
     * Retourne le stock actuel pour la taille S
     */
    public function getStockS(): int { return $this->stockS; }

    /** 
     * STOCK S
     * Met à jour le stock S
     */
    public function setStockS(int $stockS): self { $this->stockS = $stockS; return $this; }

    /** 
     * STOCK M
     * Retourne le stock actuel pour la taille M
     */
    public function getStockM(): int { return $this->stockM; }

    /** 
     * STOCK M
     * Met à jour le stock M
     */
    public function setStockM(int $stockM): self { $this->stockM = $stockM; return $this; }

    /** 
     * STOCK L
     * Retourne le stock actuel pour la taille L
     */
    public function getStockL(): int { return $this->stockL; }

    /** 
     * STOCK L
     * Met à jour le stock L
     */
    public function setStockL(int $stockL): self { $this->stockL = $stockL; return $this; }

    /** 
     * STOCK XL
     * Retourne le stock actuel pour la taille XL
     */
    public function getStockXL(): int { return $this->stockXL; }

    /** 
     * STOCK XL
     * Met à jour le stock XL
     */
    public function setStockXL(int $stockXL): self { $this->stockXL = $stockXL; return $this; }
}