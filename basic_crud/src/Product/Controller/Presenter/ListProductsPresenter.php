<?php
declare(strict_types=1);

namespace App\Product\Controller\Presenter;

use App\Product\UseCase\Response\ProductsResponse;
use DateTimeInterface;

final class ListProductsPresenter
{
    /** @return list<array<string, mixed>> */
    public static function present(ProductsResponse $response): array
    {
        $data = [];
        foreach ($response->products as $product) {
            $data[] = [
                'id'        => $product->id,
                'name'      => $product->name,
                'price'     => $product->price,
                'createdAt' => $product->createdAt->format(DateTimeInterface::ATOM),
                'updatedAt' => $product->updatedAt?->format(DateTimeInterface::ATOM),
            ];
        }

        return $data;
    }
}
