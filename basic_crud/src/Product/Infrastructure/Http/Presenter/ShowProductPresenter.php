<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Presenter;

use App\Product\Application\Responses\ProductResponse;
use DateTimeInterface;

final class ShowProductPresenter
{
    /** @return array<string, mixed> */
    public static function present(ProductResponse $product): array
    {
        return [
            'id'        => $product->id,
            'name'      => $product->name,
            'price'     => $product->price,
            'createdAt' => $product->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $product->updatedAt?->format(DateTimeInterface::ATOM),
        ];
    }
}
