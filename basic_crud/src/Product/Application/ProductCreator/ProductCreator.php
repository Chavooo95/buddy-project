<?php
declare(strict_types=1);

namespace App\Product\Application\ProductCreator;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Product\Infrastructure\Http\Request\CreateProductRequest;

class ProductCreator
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateProductRequest $request): ProductResponse
    {
        $product = Product::create(
            ProductId::generate(),
            ProductName::fromStorage($request->name),
            ProductPrice::fromStorage($request->price),
        );

        $this->repository->save($product);

        return ProductResponse::fromProduct($product);
    }
}