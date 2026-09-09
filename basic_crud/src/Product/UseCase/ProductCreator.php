<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\Request\CreateProductRequest;
use DateTime;

class ProductCreator
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateProductRequest $request): Product
    {
        $product = new Product(
            ProductId::generate(),
            new ProductName($request->name),
            ProductPrice::fromRaw($request->price),
            new DateTime(),
        );

        $this->repository->save($product);

        return $product;
    }
}