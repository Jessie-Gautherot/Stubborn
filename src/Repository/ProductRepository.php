<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Product
 * 
 * Contient toutes les méthodes pour récupérer des produits depuis la base.
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupère tous les produits
     * @return Product[]
     */
    public function findAllProducts(): array
    {
        return $this->findAll();
    }

    /**
     * Récupère un produit par son ID
     * @param int 
     * @return Product|null
     */
    public function findProductById(int $id): ?Product
    {
        return $this->find($id);
    }

    /**
     * Récupère les produits mis en avant
     * @return Product[]
     */
    public function findFeatured(): array
    {
        return $this->createQueryBuilder('p') // 'p' = alias pour Product
            ->andWhere('p.featured = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les produits dans une fourchette de prix
     * @param float $min Prix minimum
     * @param float $max Prix maximum
     * @return Product[]
     */
    public function findByPriceRange(float $min, float $max): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price BETWEEN :min AND :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }
}