<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Interface\ProductRepositoryInterface;

final class ListProducts
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function __invoke(?string $search = null): array
    {
        if ($search !== null && trim($search) !== '') {
            return $this->repository->findByName($search);
        }

        $products = $this->repository->findAll();

        return is_array($products) ? $products : [];
    }
}
