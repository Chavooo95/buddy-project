<?php
declare(strict_types=1);

namespace App\Product\Application\ProductDeleter;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;

class ProductDeleter
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id): void
    {
        $product = $this->repository->find(ProductId::fromStorage($id));
        if (!$product instanceof Product) {
            throw ProductNotFoundException::withId($id);
        }

        $this->repository->remove($product);
    }
}
