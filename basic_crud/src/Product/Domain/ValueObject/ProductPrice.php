<?php
declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\ValueObject\Exception\ProductPriceException;

final readonly class ProductPrice
{
    public float $value;

    private function __construct(float $value)
    {
        if ($value <= 0) {
            throw ProductPriceException::notPositive($value);
        }
        $this->value = $value;
    }

    public static function fromStorage(float $value): self
    {
        return new self($value);
    }
}
