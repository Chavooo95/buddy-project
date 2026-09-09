<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\Request\UpdateProductRequest;

class ProductUpdater
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id, UpdateProductRequest $request): ?Product
    {
        $product = $this->repository->find(new ProductId($id));
        if (!$product instanceof Product) {
            return null;
        }

        if ($request->name !== null) {
            $product->setName(new ProductName($request->name));
        }

        if ($request->price !== null) {
            $product->setPrice(new ProductPrice($request->price));
        }

        $this->repository->save($product);

        return $product;
    }
}