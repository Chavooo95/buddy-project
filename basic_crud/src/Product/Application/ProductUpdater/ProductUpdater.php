<?php
declare(strict_types=1);

namespace App\Product\Application\ProductUpdater;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Product\Infrastructure\Http\Request\UpdateProductRequest;

class ProductUpdater
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id, UpdateProductRequest $request): void
    {
        $product = $this->repository->find(ProductId::fromStorage($id));
        if (!$product instanceof Product) {
            throw ProductNotFoundException::withId($id);
        }

        if ($request->name !== null) {
            $product->rename(ProductName::fromStorage($request->name));
        }

        if ($request->price !== null) {
            $product->changePrice(ProductPrice::fromStorage($request->price));
        }

        $this->repository->save($product);
    }
}