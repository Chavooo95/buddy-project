<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductByNameFinder;
use App\Product\UseCase\Response\ProductsResponse;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductByNameFinderTest extends TestCase
{
    public function test_it_returns_products_matching_the_exact_name(): void
    {
        $product = (new ProductBuilder())->build();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByName')
            ->with('Keyboard')
            ->willReturn([$product]);

        $useCase = new ProductByNameFinder($repository);

        $this->assertEquals(ProductsResponse::fromProducts([$product]), $useCase('Keyboard'));
    }

    public function test_it_returns_an_empty_array_when_no_product_matches(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByName')
            ->with('NonExistent')
            ->willReturn([]);

        $useCase = new ProductByNameFinder($repository);

        $this->assertEquals(ProductsResponse::fromProducts([]), $useCase('NonExistent'));
    }
}
