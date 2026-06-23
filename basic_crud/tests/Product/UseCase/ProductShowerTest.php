<?php
declare(strict_types=1);

namespace Test\Product\UseCase;

use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ProductShower;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductShowerTest extends TestCase
{
    public function test_it_returns_null_when_the_product_is_not_found(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('anything')
            ->willReturn(null);

        $useCase = new ProductShower($repository);

        $this->assertNull($useCase('anything'));
    }

    public function test_it_returns_the_product_when_found(): void
    {
        $product = (new ProductBuilder())->build();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with('01HZZZZZZZZZZZZZZZZZZZZZZZ')
            ->willReturn($product);

        $useCase = new ProductShower($repository);

        $this->assertSame($product, $useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
