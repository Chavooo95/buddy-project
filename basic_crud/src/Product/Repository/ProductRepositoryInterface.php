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
    public function findAll(): array;

    public function find(string $id): ?Product;

    public function save(Product $entity): void;

    public function remove(Product $entity): void;

    public function findByName(string $name): array;
}
