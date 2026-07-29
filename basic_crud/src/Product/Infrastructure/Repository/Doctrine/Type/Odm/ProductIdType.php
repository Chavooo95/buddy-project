<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Repository\Doctrine\Type\Odm;

use App\Product\Entity\ValueObjects\ProductId;
use Doctrine\ODM\MongoDB\Types\Type;

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

        return new ProductId((string) $value);
    }

    public function closureToMongo(): string
    {
        return '$return = $value === null ? null : $value->value;';
    }

    public function closureToPHP(): string
    {
        return '$return = $value === null ? null : new \App\Product\Entity\ValueObjects\ProductId((string) $value);';
    }
}
