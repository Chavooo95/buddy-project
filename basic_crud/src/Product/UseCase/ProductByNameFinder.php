<?php

declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;

class ProductByNameFinder
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $name): array
    {
        return $this->repository->findByName($name);
    }
}
