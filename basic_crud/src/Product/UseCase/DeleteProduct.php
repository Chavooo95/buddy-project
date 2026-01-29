<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Interface\ProductRepositoryInterface;

final class DeleteProduct
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $productId): bool
    {
        $product = $this->repository->findOneBy(['productId' => $productId]);
        if (!$product instanceof Product) {
            return false;
        }

        $this->repository->remove($product, true);

        return true;
    }
}
