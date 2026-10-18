<?php
declare(strict_types=1);

namespace App\Product\Controller\Presenter;

use App\Product\Entity\Product;
use DateTimeInterface;

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

    public static function presentCollection(iterable $products): array
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = self::present($product);
        }

        return $data;
    }
}
