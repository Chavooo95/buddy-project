<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Product as ODMProduct;
use App\Repository\Document\ProductRepository;

class ProductODMService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function findAll(): array
    {
        $products = $this->productRepository->findAll();
        return array_map(fn(ODMProduct $product) => $product->toArray(), $products);
    }

    public function find($id): ?array
    {
        $product = $this->productRepository->findById($id);
        return $product ? $product->toArray() : null;
    }

    public function create(array $data): array
    {
        $product = new ODMProduct();
        $product->setName($data['name']);
        $product->setPrice((float) $data['price']);

        $this->productRepository->save($product);

        return $product->toArray();
    }

    public function update($id, array $data): ?array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return null;
        }

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (isset($data['price'])) {
            $product->setPrice((float) $data['price']);
        }

        $this->productRepository->save($product);

        return $product->toArray();
    }

    public function delete($id): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return false;
        }

        $this->productRepository->remove($product);

        return true;
    }
}