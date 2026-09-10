<?php
declare(strict_types=1);

namespace App\Product\Request;

final readonly class CreateProductRequest
{
    public function __construct(
        public string $name,
        public float $price,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self((string) $data['name'], (float) $data['price']);
    }
}