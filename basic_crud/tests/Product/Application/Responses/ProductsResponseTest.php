<?php
declare(strict_types=1);

namespace Test\Product\Application\Responses;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Application\Responses\ProductsResponse;
use DateTimeInterface;
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

    public function test_it_serialises_every_product_in_order(): void
    {
        $first = (new ProductBuilder())->withName('First')->withPrice(1.0)->build();
        $second = (new ProductBuilder())->withName('Second')->withPrice(2.0)->build();

        $this->assertSame(
            [
                [
                    'id'        => $first->id()->value,
                    'name'      => 'First',
                    'price'     => 1.0,
                    'createdAt' => $first->createdAt()->format(DateTimeInterface::ATOM),
                    'updatedAt' => null,
                ],
                [
                    'id'        => $second->id()->value,
                    'name'      => 'Second',
                    'price'     => 2.0,
                    'createdAt' => $second->createdAt()->format(DateTimeInterface::ATOM),
                    'updatedAt' => null,
                ],
            ],
            ProductsResponse::fromProducts([$first, $second])->toArray(),
        );
    }

    public function test_it_serialises_no_products_as_an_empty_list(): void
    {
        $this->assertSame([], ProductsResponse::fromProducts([])->toArray());
    }
}
