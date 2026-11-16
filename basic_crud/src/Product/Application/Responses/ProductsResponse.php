<?php
declare(strict_types=1);

namespace App\Product\Application\Responses;

use App\Product\Domain\Product;

final readonly class ProductsResponse
{
    /** @param list<ProductResponse> $products */
    private function __construct(
        public array $products,
    ) {
    }

    /** @param iterable<Product> $products */
    public static function fromProducts(iterable $products): self
    {
        $responses = [];
        foreach ($products as $product) {
            $responses[] = ProductResponse::fromProduct($product);
        }

        return new self($responses);
    }
}
