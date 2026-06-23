<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductLister;
use PHPUnit\Framework\TestCase;

final class ProductListerTest extends TestCase
{
    public function test_it_lists_all_products_when_no_search_is_provided(): void
    {
        $first = $this->createStub(Product::class);
        $second = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('findAll')->willReturn([$first, $second]);
        $repository->expects($this->never())->method('findByPartialName');

        $useCase = new ProductLister($repository);

        $this->assertSame([$first, $second], $useCase(null));
    }

    public function test_it_searches_by_partial_name_when_a_search_term_is_provided(): void
    {
        $match = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->never())->method('findAll');
        $repository->expects($this->once())
            ->method('findByPartialName')
            ->with('Test')
            ->willReturn([$match]);

        $useCase = new ProductLister($repository);

        $this->assertSame([$match], $useCase('Test'));
    }
}
