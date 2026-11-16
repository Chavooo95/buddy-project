<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine\Type\Odm;

use App\Product\Domain\ValueObject\ProductId;
use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException;

/**
 * Maps the ProductId value object to a plain string field (Doctrine ODM / MongoDB).
 *
 * convertTo* are used on the write/query path; the closures are inlined into the
 * generated hydrator and drive the read path.
 */
final class ProductIdType extends Type
{
    public const string NAME = 'product_id';

    public function convertToDatabaseValue($value)
    {
        if ($value instanceof ProductId) {
            return $value->value;
        }

        return $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value === null || $value instanceof ProductId) {
            return $value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('%s expects a string, got %s', self::NAME, get_debug_type($value)));
        }

        return ProductId::fromStorage($value);
    }

    public function closureToMongo(): string
    {
        return '$return = $value === null ? null : $value->value;';
    }

    public function closureToPHP(): string
    {
        return '$return = $value === null ? null : new \App\Product\Domain\ValueObject\ProductId((string) $value);';
    }
}
