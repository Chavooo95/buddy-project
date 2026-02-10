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
    public function findAll(): array;

    /**
     * Find product by ID
     */
    public function find(string $id): ?Product;

    /**
     * Save product
     */
    public function save(Product $entity): void;

    /**
     * Remove product
     */
    public function remove(Product $entity): void;

    /**
     * Find products by name (partial match)
     *
     * @return Product[]
     */
    public function findByName(string $name): array;
}
