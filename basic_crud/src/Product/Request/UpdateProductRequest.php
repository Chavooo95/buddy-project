<?php
declare(strict_types=1);

namespace App\Product\Request;

final readonly class UpdateProductRequest
{
    public function __construct(
        public ?string $name = null,
        public ?float $price = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['name']) ? (string) $data['name'] : null,
            isset($data['price']) ? (float) $data['price'] : null,
        );
    }
}