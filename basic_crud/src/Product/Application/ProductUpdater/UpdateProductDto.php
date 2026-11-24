<?php
declare(strict_types=1);

namespace App\Product\Application\ProductUpdater;

final readonly class UpdateProductDto
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?float $price = null,
    ) {
    }
}
