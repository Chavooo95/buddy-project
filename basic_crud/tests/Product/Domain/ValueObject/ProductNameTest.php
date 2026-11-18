<?php
declare(strict_types=1);

namespace Test\Product\Domain\ValueObject;

use App\Product\Domain\ValueObject\Exception\ProductNameException;
use App\Product\Domain\ValueObject\ProductName;
use PHPUnit\Framework\TestCase;

final class ProductNameTest extends TestCase {

    public function test_check_valueObject_has_the_same_value(): void
    {
        $productNameFirst = ProductName::fromStorage('Test Product');
        $productNameSecond = ProductName::fromStorage('Test Product');
        $this->assertEquals($productNameFirst, $productNameSecond);
    }

    public function test_that_valueObjects_trim_the_name(): void
    {
        $name = '   Test Product   ';
        $productName = ProductName::fromStorage($name);
        $this->assertEquals('Test Product', $productName->value);
    }

    public function test_throws_ProductNameException_on_empty_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        ProductName::fromStorage('');
    }

    public function test_rejects_whitespace_only_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        ProductName::fromStorage('   ');
    }

    public function test_throws_ProductNameException_with_less_than_three_chars(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('needs to be at least 3 characters');
        ProductName::fromStorage('as');
    }
}
