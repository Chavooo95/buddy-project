<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Interface\ProductRepositoryInterface;

final class ShowProduct
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $productId): ?Product
    {
        $product = $this->repository->findOneBy(['productId' => $productId]);

        return $product instanceof Product ? $product : null;
    }
}
