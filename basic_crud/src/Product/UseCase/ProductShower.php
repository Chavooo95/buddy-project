<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Repository\ProductRepositoryInterface;

class ProductShower
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id): ?Product
    {
        $product = $this->repository->find(new ProductId($id));

        return $product instanceof Product ? $product : null;
    }
}
