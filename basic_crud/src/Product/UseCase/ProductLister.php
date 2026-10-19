<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;

class ProductLister
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /** @return list<Product> */
    public function __invoke(?string $search = null): array
    {
        if ($search !== null && trim($search) !== '') {
            return $this->repository->findByPartialName($search);
        }

        return $this->repository->findAll();
    }
}
