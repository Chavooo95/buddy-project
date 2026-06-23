<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductDeleter;
use PHPUnit\Framework\TestCase;

final class ProductDeleterTest extends TestCase
{
    public function test_it_returns_false_when_the_product_is_not_found(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('x')
            ->willReturn(null);
        $repository->expects($this->never())->method('remove');

        $useCase = new ProductDeleter($repository);

        $this->assertFalse($useCase('x'));
    }

    public function test_it_removes_the_product_and_returns_true_when_found(): void
    {
        $product = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);
        $repository->expects($this->once())->method('remove')->with($product);

        $useCase = new ProductDeleter($repository);

        $this->assertTrue($useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
