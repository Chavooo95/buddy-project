<?php
declare(strict_types=1);

namespace Test\Product\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductIdException;
use App\Product\Entity\ValueObjects\ProductId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class ProductIdTest extends TestCase
{
    public function test_check_valueObject_has_the_same_value(): void
    {
        $idFirst = new ProductId('01HZZZZZZZZZZZZZZZZZZZZZZZ');
        $idSecond = new ProductId('01HZZZZZZZZZZZZZZZZZZZZZZZ');
        $this->assertEquals($idFirst, $idSecond);
    }

    public function test_that_valueObject_trims_the_value(): void
    {
        $id = new ProductId('   01HZZZZZZZZZZZZZZZZZZZZZZZ   ');
        $this->assertEquals('01HZZZZZZZZZZZZZZZZZZZZZZZ', $id->value);
    }

    public function test_generate_produces_a_valid_ulid(): void
    {
        $id = ProductId::generate();
        $this->assertTrue(Ulid::isValid($id->value));
    }

    public function test_it_is_castable_to_string(): void
    {
        $id = new ProductId('01HZZZZZZZZZZZZZZZZZZZZZZZ');
        $this->assertSame('01HZZZZZZZZZZZZZZZZZZZZZZZ', (string) $id);
    }

    public function test_throws_ProductIdException_on_empty_value(): void
    {
        $this->expectException(ProductIdException::class);
        $this->expectExceptionMessage('Product id cannot be empty');
        new ProductId('');
    }

    public function test_rejects_whitespace_only_value(): void
    {
        $this->expectException(ProductIdException::class);
        $this->expectExceptionMessage('Product id cannot be empty');
        new ProductId('   ');
    }
}
