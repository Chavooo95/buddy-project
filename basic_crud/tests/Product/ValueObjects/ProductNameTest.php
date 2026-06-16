<?php
declare(strict_types=1);

namespace Test\Product\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductNameException;
use PHPUnit\Framework\TestCase;
use App\Product\Entity\ValueObjects\ProductName;

final class ProductNameTest extends TestCase {

    public function test_check_valueObject_has_the_same_value(): void
    {
        $productNameFirst = new ProductName('Test Product');
        $productNameSecond = new ProductName('Test Product');
        $this->assertEquals($productNameFirst, $productNameSecond);
    }

    public function test_that_valueObjects_trim_the_name(): void
    {
        $name = '   Test Product   ';
        $productName = new ProductName($name);
        $this->assertEquals('Test Product', $productName->value);
    }

    public function test_throws_ProductNameException_on_empty_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new ProductName('');
    }

    public function test_rejects_whitespace_only_name(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new ProductName('   ');
    }

    public function test_throws_ProductNameException_with_less_than_three_chars(): void
    {
        $this->expectException(ProductNameException::class);
        $this->expectExceptionMessage('needs to be at least 3 characters');
        new ProductName('as');
    }
}
