<?php
declare(strict_types=1);

namespace App\Product\UseCase\Response;

use App\Product\Entity\Product;
use DateTimeInterface;

final readonly class ProductResponse
{
    private function __construct(
        public string $id,
        public string $name,
        public float $price,
        public DateTimeInterface $createdAt,
        public ?DateTimeInterface $updatedAt,
    ) {
    }

    public static function fromProduct(Product $product): self
    {
        return new self(
            $product->id()->value,
            $product->name()->value,
            $product->price()->value,
            $product->createdAt(),
            $product->updatedAt(),
        );
    }
}
