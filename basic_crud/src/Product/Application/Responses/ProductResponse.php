<?php
declare(strict_types=1);

namespace App\Product\Application\Responses;

use App\Product\Domain\Product;
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
            $product->price()->rounded(),
            $product->createdAt(),
            $product->updatedAt(),
        );
    }

    /** @return array{id: string, name: string, price: float, createdAt: string, updatedAt: string|null} */
    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'price'     => $this->price,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(DateTimeInterface::ATOM),
        ];
    }
}
