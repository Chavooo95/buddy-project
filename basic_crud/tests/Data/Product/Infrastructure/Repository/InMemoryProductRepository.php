<?php

declare(strict_types=1);

namespace Test\Data\Product\Infrastructure\Repository;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products = [];

    public function findAll(): array
    {
        return array_values($this->products);
    }

    public function find(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function save(Product $entity): void
    {
        $this->products[$entity->id()] = $entity;
    }

    public function remove(Product $entity): void
    {
        unset($this->products[$entity->id()]);
    }

    public function findByName(string $name): array
    {
        return array_values(array_filter(
            $this->products,
            fn(Product $product) => $product->name()?->value === $name
        ));
    }

    public function findByPartialName(string $name): array
    {
        return array_values(array_filter(
            $this->products,
            fn(Product $product) => str_contains($product->name()?->value ?? '', $name)
        ));
    }
}
