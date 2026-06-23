<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductUpdater;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductUpdaterTest extends TestCase
{
    public function test_it_returns_null_when_the_product_is_not_found(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('x')
            ->willReturn(null);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductUpdater($repository);

        $this->assertNull($useCase('x', ['name' => 'New']));
    }

    public function test_it_updates_and_saves_the_product_when_found(): void
    {
        $product = $this->createMock(Product::class);
        $product->expects($this->once())
            ->method('setName')
            ->with(new ProductName('New Name'))
            ->willReturnSelf();
        $product->expects($this->once())
            ->method('setPrice')
            ->with(new ProductPrice(99.99))
            ->willReturnSelf();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);
        $repository->expects($this->once())->method('save')->with($product);

        $useCase = new ProductUpdater($repository);

        $result = $useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ', ['name' => 'New Name', 'price' => 99.99]);

        $this->assertSame($product, $result);
    }

    public function test_it_rejects_an_empty_name(): void
    {
        $product = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('find')->willReturn($product);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductUpdater($repository);

        $this->expectException(InvalidArgumentException::class);
        $useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ', ['name' => '   ']);
    }
}
