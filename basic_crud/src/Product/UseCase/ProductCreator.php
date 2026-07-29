<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;
use DateTime;
use InvalidArgumentException;

class ProductCreator
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    /** @param array{name: string, price: float} $data */
    public function __invoke(array $data): Product
    {
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Product name is required');
        }
        if (!isset($data['price'])) {
            throw new InvalidArgumentException('Product price is required');
        }

        $product = new Product(
            ProductId::generate(),
            new ProductName($data['name']),
            new ProductPrice($data['price']),
            new DateTime(),
        );

        $this->repository->save($product);

        return $product;
    }
}
