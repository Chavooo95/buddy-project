<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use InvalidArgumentException;

final class UpdateProduct
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $id, array $data): ?Product
    {
        $product = $this->repository->find($id);
        if (!$product instanceof Product) {
            return null;
        }

        if (array_key_exists('name', $data)) {
            if (trim((string) $data['name']) === '') {
                throw new InvalidArgumentException('Product name cannot be empty');
            }
            $product->setName((string) $data['name']);
        }

        if (array_key_exists('price', $data)) {
            $price = (float) $data['price'];
            if ($price < 0) {
                throw new InvalidArgumentException('Product price cannot be negative');
            }
            $product->setPrice($price);
        }

        $this->repository->save($product);

        return $product;
    }
}
