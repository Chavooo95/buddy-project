<?php
declare(strict_types=1);

namespace Test\Product\Application\ProductDeleter;

use App\Product\Application\ProductDeleter\ProductDeleter;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class ProductDeleterTest extends TestCase
{
    public function test_it_throws_when_the_product_is_not_found(): void
    {
        $id = (string) new Ulid();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage($id))
            ->willReturn(null);
        $repository->expects($this->never())->method('remove');

        $useCase = new ProductDeleter($repository);

        $this->expectException(ProductNotFoundException::class);

        $useCase($id);
    }

    public function test_it_removes_the_product_when_found(): void
    {
        $product = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage('01HZZZZZZZZZZZZZZZZZZZZZZZ'))
            ->willReturn($product);
        $repository->expects($this->once())->method('remove')->with($product);

        $useCase = new ProductDeleter($repository);

        $useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ');
    }
}
