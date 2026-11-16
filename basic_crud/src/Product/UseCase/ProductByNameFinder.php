<?php

declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\Response\ProductsResponse;

class ProductByNameFinder
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $name): ProductsResponse
    {
        return ProductsResponse::fromProducts($this->repository->findByName($name));
    }
}
