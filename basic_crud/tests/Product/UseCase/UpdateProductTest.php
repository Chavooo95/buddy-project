<?php
declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Interface\ProductRepositoryInterface;
use App\Product\UseCase\UpdateProduct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UpdateProductTest extends TestCase
{
    public function testReturnsNullWhenProductNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->with(['productId' => 'x'])
            ->willReturn(null);
        $repo->expects($this->never())->method('save');

        $uc = new UpdateProduct($repo);

        $this->assertNull($uc('x', ['name' => 'New']));
    }

    public function testUpdatesAndSavesWhenFound(): void
    {
        $product = $this->createMock(Product::class);
        $product->expects($this->once())->method('setName')->with('New Name')->willReturnSelf();
        $product->expects($this->once())->method('setPrice')->with(99.99)->willReturnSelf();

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findOneBy')
            ->with(['productId' => '01HZZZZZZZZZZZZZZZZZZZZZZZ'])
            ->willReturn($product);
        $repo->expects($this->once())->method('save')->with($product, true);

        $uc = new UpdateProduct($repo);

        $result = $uc('01HZZZZZZZZZZZZZZZZZZZZZZZ', ['name' => 'New Name', 'price' => 99.99]);

        $this->assertSame($product, $result);
    }

    public function testRejectsEmptyName(): void
    {
        $product = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())->method('findOneBy')->willReturn($product);
        $repo->expects($this->never())->method('save');

        $uc = new UpdateProduct($repo);

        $this->expectException(InvalidArgumentException::class);
        $uc('01HZZZZZZZZZZZZZZZZZZZZZZZ', ['name' => '   ']);
    }
}