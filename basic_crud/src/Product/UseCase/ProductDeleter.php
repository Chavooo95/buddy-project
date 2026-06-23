<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;

class ProductDeleter
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id): bool
    {
        $product = $this->repository->find($id);
        if (!$product instanceof Product) {
            return false;
        }

        $this->repository->remove($product);

        return true;
    }
}
