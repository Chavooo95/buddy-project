<?php

declare(strict_types=1);

namespace Test\Data\Product\Infrastructure\Repository;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Repository\ProductRepositoryInterface;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products = [];

    public function findAll(): array
    {
        return array_values($this->products);
    }

    public function find(ProductId $id): ?Product
    {
        return $this->products[$id->value] ?? null;
    }

    public function save(Product $entity): void
    {
        $this->products[$entity->id()->value] = $entity;
    }

    public function remove(Product $entity): void
    {
        unset($this->products[$entity->id()->value]);
    }

    public function findByName(string $name): array
    {
        return array_values(array_filter(
            $this->products,
            fn(Product $product) => $product->name()->value === $name
        ));
    }

    public function findByPartialName(string $name): array
    {
        return array_values(array_filter(
            $this->products,
            fn(Product $product) => str_contains($product->name()->value, $name)
        ));
    }
}
