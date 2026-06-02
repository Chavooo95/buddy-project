<?php
declare(strict_types=1);

namespace Test\Product\ValueObjects;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Product\Entity\ValueObjects\ProductName;

final class ProductNameTest extends TestCase {

    public function test_stores_the_value(): void
    {
        $productName = new ProductName('Test Product');
        $this->assertSame('Test Product', $productName->value);
    }
    public function test_trimmed_name()
    {
        $name = '   Test Product   ';
        $productName = new ProductName($name);
        $this->assertEquals('Test Product', $productName->value);
    }

    public function test_rejects_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new ProductName('');
    }

    public function test_rejects_whitespace_only_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new ProductName('   ');
    }
}
