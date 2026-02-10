<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Product\Entity\Product;

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
    public function find(string $id);

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
}
