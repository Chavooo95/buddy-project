<?php
declare(strict_types=1);

namespace App\Interface;

use App\Entity\Product;

/**
 * Product Repository Interface
 * Contract for Product repository implementations
 */
interface ProductRepositoryInterface
{
    /**
     * Find all products
     * 
     * @return Product[]
     */
    public function findAll();

    /**
     * Find product by ID
     */
    public function find(mixed $id);

    /**
     * Find products by criteria
     * 
     * @return Product[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    /**
     * Find one product by criteria
     */
    public function findOneBy(array $criteria, ?array $orderBy = null);

    /**
     * Save product
     */
    public function save(Product $entity, bool $flush = false): void;

    /**
     * Remove product
     */
    public function remove(Product $entity, bool $flush = false): void;

    /**
     * Find products by name (partial match)
     * 
     * @return Product[]
     */
    public function findByName(string $name): array;

    /**
     * Find products by price range
     * 
     * @return Product[]
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array;

    /**
     * Find products created after a specific date
     * 
     * @return Product[]
     */
    public function findCreatedAfter(\DateTimeInterface $date): array;
}