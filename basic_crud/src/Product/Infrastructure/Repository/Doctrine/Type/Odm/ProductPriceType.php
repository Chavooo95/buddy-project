<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Repository\Doctrine\Type\Odm;

use App\Product\Entity\ValueObjects\ProductPrice;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Maps the ProductPrice value object to a float field (Doctrine ODM / MongoDB).
 *
 * convertTo* are used on the write/query path; the closures are inlined into the
 * generated hydrator and drive the read path.
 */
final class ProductPriceType extends Type
{
    public const string NAME = 'product_price';

    public function convertToDatabaseValue($value)
    {
        if ($value instanceof ProductPrice) {
            return $value->value;
        }

        return $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value === null || $value instanceof ProductPrice) {
            return $value;
        }

        return new ProductPrice((float) $value);
    }

    public function closureToMongo(): string
    {
        return '$return = $value === null ? null : $value->value;';
    }

    public function closureToPHP(): string
    {
        return '$return = $value === null ? null : new \App\Product\Entity\ValueObjects\ProductPrice((float) $value);';
    }
}
