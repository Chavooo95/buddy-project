<?php
declare(strict_types=1);

namespace Test\Product\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductNameException;
use PHPUnit\Framework\TestCase;
use App\Product\Entity\ValueObjects\ProductName;

final class ProductNameTest extends TestCase {

    public function test_check_valueObject_has_the_same_value(): void
    {
        $productNameFirst = ProductName::fromString('Test Product');
        $productNameSecond = ProductName::fromString('Test Product');
        $this->assertEquals($productNameFirst, $productNameSecond);
    }

    public function test_that_valueObjects_trim_the_name(): void
    {
        $name = '   Test Product   ';
        $productName = ProductName::fromString($name);
        $this->assertEquals('Test Product', $productName->value);
    }

    public function test_throws_ProductNameException_on_empty_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        ProductName::fromString('');
    }

    public function test_rejects_whitespace_only_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        ProductName::fromString('   ');
    }

    public function test_throws_ProductNameException_with_less_than_three_chars(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('needs to be at least 3 characters');
        ProductName::fromString('as');
    }
}
