<?php
declare(strict_types=1);

namespace Test\Product\UseCase\Response;

use App\Product\Entity\ValueObjects\ProductName;
use App\Product\UseCase\Response\ProductResponse;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductResponseTest extends TestCase
{
    public function test_it_exposes_the_product_as_primitives(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->withPrice(12.5)->build();

        $response = ProductResponse::fromProduct($product);

        $this->assertSame($product->id()->value, $response->id);
        $this->assertSame('Keyboard', $response->name);
        $this->assertSame(12.5, $response->price);
        $this->assertSame($product->createdAt(), $response->createdAt);
        $this->assertNull($response->updatedAt);
    }

    public function test_it_carries_updatedAt_once_the_product_changed(): void
    {
        $product = (new ProductBuilder())->build();
        $product->rename(ProductName::fromStorage('Renamed'));

        $response = ProductResponse::fromProduct($product);

        $this->assertSame($product->updatedAt(), $response->updatedAt);
    }
}
