<?php
declare(strict_types=1);

namespace App\Product\Application\ProductShower;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;

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
