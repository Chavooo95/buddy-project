<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects\Exception;

use InvalidArgumentException;

final class ProductPriceException extends InvalidArgumentException
{
    private function __construct(string $message)
    {
        return parent::__construct($message);
    }

    public static function notPositive(float $value): self
    {
        return new self(
            sprintf('Product price "%s" must be greater than zero', $value)
        );
    }

    public static function invalidType(mixed $value): self
    {
        return new self(
            sprintf('Product price must be numeric, got %s', get_debug_type($value))
        );
    }
}
