<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductCreator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Infrastructure\Repository\InMemoryProductRepository;

final class ProductCreatorTest extends TestCase
{
    public function test_it_creates_and_saves_a_product(): void
    {
        $repository = new InMemoryProductRepository();
        $useCase = new ProductCreator($repository);

        $product = $useCase(['name' => 'Keyboard', 'price' => 12.34]);

        $this->assertSame($product, $repository->findByName('Keyboard')[0]);
        $this->assertEquals('Keyboard', $product->name()->value);
        $this->assertEquals(12.34, $product->price()->value);
    }

    public function test_it_rejects_a_missing_name(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductCreator($repository);

        $this->expectException(InvalidArgumentException::class);
        $useCase(['price' => 1]);
    }

    public function test_it_rejects_a_negative_price(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductCreator($repository);

        $this->expectException(InvalidArgumentException::class);
        $useCase(['name' => 'Keyboard', 'price' => -1]);
    }
}
