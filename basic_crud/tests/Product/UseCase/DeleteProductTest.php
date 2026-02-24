<?php
declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\DeleteProduct;
use PHPUnit\Framework\TestCase;

final class DeleteProductTest extends TestCase
{
    public function testReturnsFalseWhenNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('x')
            ->willReturn(null);
        $repo->expects($this->never())->method('remove');

        $uc = new DeleteProduct($repo);

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

        $uc = new DeleteProduct($repo);

        $this->assertTrue($uc('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
