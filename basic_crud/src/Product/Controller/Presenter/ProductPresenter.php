<?php
declare(strict_types=1);

namespace App\Product\Controller\Presenter;

use App\Product\Entity\Product;
use DateTimeInterface;

/**
 * The one place that decides how a Product looks in an HTTP response.
 *
 * Kept out of the entity on purpose: the domain shouldn't change because the
 * API wants a different shape, and every endpoint must agree on that shape.
 */
final class ProductPresenter
{
    /** @return array<string, mixed> */
    public static function present(Product $product): array
    {
        return [
            'id'        => $product->id()->value,
            'name'      => $product->name()->value,
            'price'     => $product->price()->value,
            'createdAt' => $product->createdAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $product->updatedAt()?->format(DateTimeInterface::ATOM),
        ];
    }

    /**
     * @param iterable<Product> $products
     * @return list<array<string, mixed>>
     */
    public static function presentCollection(iterable $products): array
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = self::present($product);
        }

        return $data;
    }
}
