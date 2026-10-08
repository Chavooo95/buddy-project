<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\Exception\ProductNotFoundException;

class ProductDeleter
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id): void
    {
        $product = $this->repository->find(new ProductId($id));
        if (!$product instanceof Product) {
            throw ProductNotFoundException::withId($id);
        }

        $this->repository->remove($product);
    }
}
