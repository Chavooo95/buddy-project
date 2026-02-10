<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use InvalidArgumentException;

final class CreateProduct
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): Product
    {
        $this->validate($data);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice((float) $data['price']);

        $this->repository->save($product, true);

        return $product;
    }

    private function validate(array $data): void
    {
        if (!isset($data['name']) || trim((string) $data['name']) === '') {
            throw new InvalidArgumentException('Product name is required');
        }

        if (!isset($data['price'])) {
            throw new InvalidArgumentException('Product price is required');
        }

        $price = (float) $data['price'];
        if ($price < 0) {
            throw new InvalidArgumentException('Product price cannot be negative');
        }
    }
}
