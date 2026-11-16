<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Presenter;

use App\Product\Application\Responses\ProductsResponse;
use DateTimeInterface;

final class FindProductsByNamePresenter
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
