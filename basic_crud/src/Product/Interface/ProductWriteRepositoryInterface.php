<?php
declare(strict_types=1);

namespace App\Product\Interface;

use App\Product\Entity\Product;

interface ProductWriteRepositoryInterface
{
    public function findOneByProductId(string $productId): ?Product;

    public function save(Product $entity, bool $flush = false): void;

    public function remove(Product $entity, bool $flush = false): void;
}