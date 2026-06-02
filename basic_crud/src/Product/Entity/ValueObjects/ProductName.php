<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects;

use InvalidArgumentException;

final readonly class ProductName
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);
        if ($this->value === '') {
            throw new InvalidArgumentException('Product name cannot be empty');
        }
    }
}
