<?php
declare(strict_types=1);

namespace Test\Product\Domain\ValueObject;

use App\Product\Domain\ValueObject\Exception\ProductPriceException;
use App\Product\Domain\ValueObject\ProductPrice;
use PHPUnit\Framework\TestCase;

final class ProductPriceTest extends TestCase {

    public function test_check_valueObject_has_the_same_value(): void
    {
        $productPriceFirst = ProductPrice::fromStorage(19.99);
        $productPriceSecond = ProductPrice::fromStorage(19.99);
        $this->assertEquals($productPriceFirst, $productPriceSecond);
    }

    public function test_that_valueObject_keeps_the_value(): void
    {
        $productPrice = ProductPrice::fromStorage(19.99);
        $this->assertEquals(19.99, $productPrice->exact());
    }

    public function test_throws_ProductPriceException_on_zero_price(): void
    {
        $this->expectException(ProductPriceException::class);
        $this->expectExceptionMessage('must be greater than zero');
        ProductPrice::fromStorage(0);
    }

    public function test_throws_ProductPriceException_on_negative_price(): void
    {
        $this->expectException(ProductPriceException::class);
        $this->expectExceptionMessage('must be greater than zero');
        ProductPrice::fromStorage(-5.0);
    }

    public function test_exact_keeps_every_decimal(): void
    {
        $productPrice = ProductPrice::fromStorage(19.994);
        $this->assertSame(19.994, $productPrice->exact());
    }

    public function test_rounded_keeps_two_decimals(): void
    {
        $productPrice = ProductPrice::fromStorage(19.995);
        $this->assertSame(20.0, $productPrice->rounded());
    }

    public function test_rounded_absorbs_floating_point_noise(): void
    {
        $productPrice = ProductPrice::fromStorage(0.1 + 0.2);
        $this->assertSame(0.3, $productPrice->rounded());
    }
}
