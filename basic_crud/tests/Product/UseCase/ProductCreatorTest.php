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
    public function testCreatesAndSavesProduct(): void
    {
        $repo = new InMemoryProductRepository();
        $subject = new ProductCreator($repo);

        $product = $subject(['name' => 'Keyboard', 'price' => 12.34]);

        $this->assertSame($product, $repo->findByName('Keyboard')[0]);
        $this->assertEquals('Keyboard', $product->name());
        $this->assertEquals(12.34, $product->price());
        // dd($repo->find($product->id()));
    }

    public function testRejectsMissingName(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->never())->method('save');

        $uc = new ProductCreator($repo);

        $this->expectException(InvalidArgumentException::class);
        $uc(['price' => 1]);
    }

    public function testRejectsNegativePrice(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->never())->method('save');

        $uc = new ProductCreator($repo);

        $this->expectException(InvalidArgumentException::class);
        $uc(['name' => 'Keyboard', 'price' => -1]);
    }
}
