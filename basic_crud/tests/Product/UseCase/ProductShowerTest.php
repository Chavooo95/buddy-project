<?php

declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductShower;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductShowerTest extends TestCase
{
    public function testReturnsNullWhenNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('anything')
            ->willReturn(null);

        $uc = new ProductShower($repo);

        $this->assertNull($uc('anything'));
    }

    public function testReturnsProductWhenFound(): void
    {
        $product = (new ProductBuilder())->build();

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);

        $uc = new ProductShower($repo);

        $this->assertSame($product, $uc('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
