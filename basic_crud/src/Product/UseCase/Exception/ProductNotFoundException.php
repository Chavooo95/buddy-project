<?php
declare(strict_types=1);

namespace App\Product\UseCase\Exception;

use RuntimeException;

final class ProductNotFoundException extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withId(string $id): self
    {
        return new self(sprintf('Product "%s" not found', $id));
    }
}
