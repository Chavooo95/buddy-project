<?php
declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ListProducts;
use PHPUnit\Framework\TestCase;

final class ListProductsTest extends TestCase
{
    public function testListsAllProductsWhenNoSearchProvided(): void
    {
        $p1 = $this->createMock(Product::class);
        $p2 = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())->method('findAll')->willReturn([$p1, $p2]);
        $repo->expects($this->never())->method('findByName');

        $uc = new ListProducts($repo);

        $result = $uc(null);

        $this->assertCount(2, $result);
        $this->assertSame([$p1, $p2], $result);
    }

    public function testSearchesWhenSearchProvided(): void
    {
        $p1 = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->never())->method('findAll');
        $repo->expects($this->once())->method('findByName')->with('Test')->willReturn([$p1]);

        $uc = new ListProducts($repo);

        $result = $uc('Test');

        $this->assertSame([$p1], $result);
    }
}
