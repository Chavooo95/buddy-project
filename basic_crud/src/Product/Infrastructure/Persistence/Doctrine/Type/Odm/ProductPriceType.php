<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine\Type\Odm;

use App\Product\Domain\ValueObject\ProductPrice;
use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException;

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
            return $value->exact();
        }

        return $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value === null || $value instanceof ProductPrice) {
            return $value;
        }

        if (!is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('%s expects numeric, got %s', self::NAME, get_debug_type($value)));
        }

        return ProductPrice::fromStorage((float) $value);
    }

    public function closureToMongo(): string
    {
        return '$return = $value === null ? null : $value->exact();';
    }

    public function closureToPHP(): string
    {
        return '$return = $value === null ? null : new \App\Product\Domain\ValueObject\ProductPrice((float) $value);';
    }
}
