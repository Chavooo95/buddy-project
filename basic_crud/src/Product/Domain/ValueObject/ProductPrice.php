<?php
declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\ValueObject\Exception\ProductPriceException;

final readonly class ProductPrice
{
    private const int MAX_DECIMALS = 2;

    public float $value;

    private function __construct(float $value)
    {
        $this->value = round($value, self::MAX_DECIMALS);
        if ($this->value <= 0) {
            throw ProductPriceException::notPositive($this->value);
        }
    }

    public static function fromStorage(float $value): self
    {
        return new self($value);
    }
}
