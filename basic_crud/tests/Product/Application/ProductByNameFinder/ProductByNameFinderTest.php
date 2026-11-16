<?php
declare(strict_types=1);

namespace Test\Product\Application\ProductByNameFinder;

use App\Product\Application\ProductByNameFinder\ProductByNameFinder;
use App\Product\Application\Responses\ProductsResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;
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
