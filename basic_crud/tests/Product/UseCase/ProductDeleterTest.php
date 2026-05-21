<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductDeleter;
use PHPUnit\Framework\TestCase;

final class ProductDeleterTest extends TestCase
{
    public function testReturnsFalseWhenNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('x')
            ->willReturn(null);
        $repo->expects($this->never())->method('remove');

        $uc = new ProductDeleter($repo);

        $this->assertFalse($uc('x'));
    }

    public function testRemovesAndReturnsTrueWhenFound(): void
    {
        $product = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);
        $repo->expects($this->once())->method('remove')->with($product, true);

        $uc = new ProductDeleter($repo);

        $this->assertTrue($uc('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
