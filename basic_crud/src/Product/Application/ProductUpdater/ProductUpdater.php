<?php
declare(strict_types=1);

namespace App\Product\Application\ProductUpdater;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;

class ProductUpdater
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateProductDto $dto): void
    {
        $product = $this->repository->find(ProductId::fromStorage($dto->id));
        if (!$product instanceof Product) {
            throw ProductNotFoundException::withId($dto->id);
        }

        if ($dto->name !== null) {
            $product->rename(ProductName::fromStorage($dto->name));
        }

        if ($dto->price !== null) {
            $product->changePrice(ProductPrice::fromStorage($dto->price));
        }

        $this->repository->save($product);
    }
}