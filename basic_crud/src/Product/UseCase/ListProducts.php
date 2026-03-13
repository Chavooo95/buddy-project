<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;

class ListProducts
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string|null $search
     * @return array
     */
    public function __invoke(?string $search = null): array
    {
        if ($search !== null && trim($search) !== '') {
            return $this->repository->findByName($search);
        }

        return $this->repository->findAll();
    }
}
