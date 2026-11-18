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

    /** @return list<array{id: string, name: string, price: float, createdAt: string, updatedAt: string|null}> */
    public function toArray(): array
    {
        return array_map(static fn (ProductResponse $product): array => $product->toArray(), $this->products);
    }
}
