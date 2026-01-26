<?php
declare(strict_types=1);

namespace App\Product\Interface;

use App\Product\Entity\Product;

interface ProductReadRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function listAll(?string $search = null): array;

    public function findOneByProductId(string $productId): ?Product;
}