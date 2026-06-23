<?php
declare(strict_types=1);

namespace App\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;

class ProductUpdater
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
            $product->setName(new ProductName((string) $data['name']));
        }

        if (array_key_exists('price', $data)) {
            $product->setPrice(new ProductPrice((float) $data['price']));
        }

        $this->repository->save($product);

        return $product;
    }
}
