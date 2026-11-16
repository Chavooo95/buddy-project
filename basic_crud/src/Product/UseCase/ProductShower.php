<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\Response\ProductResponse;

class ProductShower
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id): ?ProductResponse
    {
        $product = $this->repository->find(ProductId::fromStorage($id));

        return $product instanceof Product ? ProductResponse::fromProduct($product) : null;
    }
}
