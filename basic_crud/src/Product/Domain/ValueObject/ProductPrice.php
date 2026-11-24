<?php
declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\ValueObject\Exception\ProductPriceException;

final readonly class ProductPrice
{
    private const int DISPLAY_DECIMALS = 2;

    private float $value;

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

    public function exact(): float
    {
        return $this->value;
    }

    public function rounded(): float
    {
        return round($this->value, self::DISPLAY_DECIMALS);
    }
}
