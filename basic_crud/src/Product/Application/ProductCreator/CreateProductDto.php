<?php
declare(strict_types=1);

namespace App\Product\Application\ProductCreator;

final readonly class CreateProductDto
{
    public function __construct(
        public string $name,
        public float $price,
    ) {
    }
}
