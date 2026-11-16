<?php
declare(strict_types=1);

namespace Test\Product\Application\Responses;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Application\Responses\ProductsResponse;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductsResponseTest extends TestCase
{
    public function test_it_wraps_every_product_in_order(): void
    {
        $first = (new ProductBuilder())->withName('First')->build();
        $second = (new ProductBuilder())->withName('Second')->build();

        $response = ProductsResponse::fromProducts([$first, $second]);

        $this->assertEquals(
            [ProductResponse::fromProduct($first), ProductResponse::fromProduct($second)],
            $response->products,
        );
    }

    public function test_it_is_empty_when_there_are_no_products(): void
    {
        $this->assertSame([], ProductsResponse::fromProducts([])->products);
    }
}
