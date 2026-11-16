<?php
declare(strict_types=1);

namespace App\Product\Application\ProductLister;

use App\Product\Application\Responses\ProductsResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;

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
