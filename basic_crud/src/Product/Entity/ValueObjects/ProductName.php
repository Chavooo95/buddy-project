<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductNameException;

final readonly class ProductName
{
    private const int MIN_LENGTH = 3;

    public string $value;

    private function __construct(string $value)
    {
        $this->value = trim($value);
        if ($this->value === '') {
            throw ProductNameException::empty();
        }
        if (strlen($this->value) < self::MIN_LENGTH) {
            throw ProductNameException::tooShort($this->value, self::MIN_LENGTH);
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
