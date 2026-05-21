<?php

declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductByNameFinder;
use PHPUnit\Framework\TestCase;

final class ProductByNameFinderTest extends TestCase
{
    public function test_it_returns_products_matching_exact_name(): void
    {
        $product = $this->createMock(Product::class);

        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByName')
            ->with('Keyboard')
            ->willReturn([$product]);

        $useCase = new ProductByNameFinder($repo);
        $result = $useCase('Keyboard');

        $this->assertSame([$product], $result);
    }

    public function test_it_returns_empty_array_when_no_match(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByName')
            ->with('NonExistent')
            ->willReturn([]);

        $useCase = new ProductByNameFinder($repo);
        $result = $useCase('NonExistent');

        $this->assertSame([], $result);
    }
}
