<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Request;

use InvalidArgumentException;

final readonly class UpdateProductRequest
{
    private function __construct(
        public ?string $name = null,
        public ?float $price = null,
    ) {
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $name = $data['name'] ?? null;
        $price = $data['price'] ?? null;

        if ($name !== null && !is_string($name)) {
            throw new InvalidArgumentException('name must be a string');
        }

        if ($price !== null && !is_numeric($price)) {
            throw new InvalidArgumentException('price must be numeric');
        }

        return new self($name, $price === null ? null : (float) $price);
    }
}
