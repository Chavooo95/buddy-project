<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\Response\ProductsResponse;

class ProductLister
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?string $search = null): ProductsResponse
    {
        if ($search !== null && trim($search) !== '') {
            return ProductsResponse::fromProducts($this->repository->findByPartialName($search));
        }

        return ProductsResponse::fromProducts($this->repository->findAll());
    }
}
