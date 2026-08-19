<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects\Exception;

use InvalidArgumentException;

final class ProductIdException extends InvalidArgumentException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function empty(): self
    {
        return new self('Product id cannot be empty');
    }

    public static function invalidFormat(string $value): self
    {
        return new self(sprintf('Product id "%s" is not a valid ULID', $value));
    }
}
