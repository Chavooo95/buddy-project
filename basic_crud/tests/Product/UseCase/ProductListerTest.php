<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductLister;
use App\Product\UseCase\Response\ProductsResponse;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductListerTest extends TestCase
{
    public function test_it_lists_all_products_when_no_search_is_provided(): void
    {
        $first = (new ProductBuilder())->build();
        $second = (new ProductBuilder())->build();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('findAll')->willReturn([$first, $second]);
        $repository->expects($this->never())->method('findByPartialName');

        $useCase = new ProductLister($repository);

        $this->assertEquals(ProductsResponse::fromProducts([$first, $second]), $useCase(null));
    }

    public function test_it_searches_by_partial_name_when_a_search_term_is_provided(): void
    {
        $match = (new ProductBuilder())->build();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->never())->method('findAll');
        $repository->expects($this->once())
            ->method('findByPartialName')
            ->with('Test')
            ->willReturn([$match]);

        $useCase = new ProductLister($repository);

        $this->assertEquals(ProductsResponse::fromProducts([$match]), $useCase('Test'));
    }
}
