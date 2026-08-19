<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductPriceException;

final readonly class ProductPrice
{
    private const int MAX_DECIMALS = 2;

    public float $value;

    public function __construct(float $value)
    {
        $this->value = round($value, self::MAX_DECIMALS);
        if ($this->value <= 0) {
            throw ProductPriceException::notPositive($this->value);
        }
    }
    public static function fromRaw(mixed $value): self
    {
        if (!is_numeric($value)) {
            throw ProductPriceException::invalidType($value);
        }

        return new self((float) $value);
    }
}
