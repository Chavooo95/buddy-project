<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Repository\Doctrine\Type\Orm;

use App\Product\Entity\ValueObjects\ProductPrice;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Maps the ProductPrice value object to a float column (Doctrine ORM / DBAL).
 */
final class ProductPriceType extends Type
{
    public const string NAME = 'product_price';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getFloatDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ProductPrice) {
            return $value->value;
        }

        return (float) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductPrice
    {
        if ($value === null || $value instanceof ProductPrice) {
            return $value;
        }

        return new ProductPrice((float) $value);
    }
}
