<?php

declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ShowProduct;
use PHPUnit\Framework\TestCase;

final class ShowProductTest extends TestCase
{
    public function testReturnsNullWhenNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('anything')
            ->willReturn(null);

        $uc = new ShowProduct($repo);

        $this->assertNull($uc('anything'));
    }

    public function testReturnsProductWhenFound(): void
    {
        $product = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);

        $uc = new ShowProduct($repo);

        $this->assertSame($product, $uc('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
