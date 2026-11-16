<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Request;

use InvalidArgumentException;

final readonly class CreateProductRequest
{
    private function __construct(
        public string $name,
        public float $price,
    ) {
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $name = $data['name'] ?? null;
        $price = $data['price'] ?? null;

        if (!is_string($name)) {
            throw new InvalidArgumentException('name must be a string');
        }

        if (!is_numeric($price)) {
            throw new InvalidArgumentException('price must be numeric');
        }

        return new self($name, (float) $price);
    }
}
