<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects\Exception;

use InvalidArgumentException;

final class ProductNameException extends InvalidArgumentException
{
    public static function empty(): self
    {
        return new self('Product name cannot be empty');
    }

    public static function tooShort(string $value, int $minLength): self
    {
        return new self(
            sprintf('Product name "%s" needs to be at least %d characters or more', $value, $minLength)
        );
    }
}
