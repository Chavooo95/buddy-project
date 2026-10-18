<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\Request\CreateProductRequest;

class ProductCreator
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateProductRequest $request): Product
    {
        $product = Product::create(
            ProductId::generate(),
            ProductName::fromString($request->name),
            ProductPrice::fromFloat($request->price),
        );

        $this->repository->save($product);

        return $product;
    }
}