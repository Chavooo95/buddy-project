<?php
declare(strict_types=1);

namespace Test\Product\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductPriceException;
use PHPUnit\Framework\TestCase;
use App\Product\Entity\ValueObjects\ProductPrice;

final class ProductPriceTest extends TestCase {

    public function test_check_valueObject_has_the_same_value(): void
    {
        $productPriceFirst = new ProductPrice(19.99);
        $productPriceSecond = new ProductPrice(19.99);
        $this->assertEquals($productPriceFirst, $productPriceSecond);
    }

    public function test_that_valueObject_keeps_the_value(): void
    {
        $productPrice = new ProductPrice(19.99);
        $this->assertEquals(19.99, $productPrice->value);
    }

    public function test_throws_ProductPriceException_on_zero_price(): void
    {
        $this->expectException(ProductPriceException::class);
        $this->expectExceptionMessage('must be greater than zero');
        new ProductPrice(0);
    }

    public function test_throws_ProductPriceException_on_negative_price(): void
    {
        $this->expectException(ProductPriceException::class);
        $this->expectExceptionMessage('must be greater than zero');
        new ProductPrice(-5.0);
    }

    public function test_that_valueObject_rounds_the_value_to_two_decimals(): void
    {
        $productPrice = new ProductPrice(19.994);
        $this->assertEquals(19.99, $productPrice->value);
    }

    public function test_that_valueObject_handles_floating_point_arithmetic(): void
    {
        $productPrice = new ProductPrice(0.1 + 0.2);
        $this->assertEquals(0.3, $productPrice->value);
    }
}
