<?php

declare(strict_types=1);

namespace App\Product\Application\ProductByNameFinder;

use App\Product\Application\Responses\ProductsResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;

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
