<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Repository\Doctrine\Type\Orm;

use App\Product\Entity\ValueObjects\ProductName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Maps the ProductName value object to a plain string column (Doctrine ORM / DBAL).
 */
final class ProductNameType extends Type
{
    public const string NAME = 'product_name';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ProductName) {
            return $value->value;
        }

        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductName
    {
        if ($value === null || $value instanceof ProductName) {
            return $value;
        }

        return new ProductName((string) $value);
    }
}
