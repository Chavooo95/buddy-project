<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product as ORMProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductORMService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findAll(): array
    {
        $products = $this->productRepository->findAll();
        return array_map(fn(ORMProduct $product) => $product->toArray(), $products);
    }

    public function find($id): ?array
    {
        $product = $this->productRepository->find($id);
        return $product ? $product->toArray() : null;
    }

    public function create(array $data): array
    {
        $product = new ORMProduct();
        $product->setName($data['name']);
        $product->setPrice((float) $data['price']);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product->toArray();
    }

    public function update($id, array $data): ?array
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return null;
        }

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (isset($data['price'])) {
            $product->setPrice((float) $data['price']);
        }

        $this->entityManager->flush();

        return $product->toArray();
    }

    public function delete($id): bool
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return false;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }
}