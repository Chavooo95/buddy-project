<?php
declare(strict_types=1);

namespace Test\Product\Application\ProductUpdater;

use App\Product\Application\ProductUpdater\ProductUpdater;
use App\Product\Application\ProductUpdater\UpdateProductDto;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

final class ProductUpdaterTest extends TestCase
{
    public function test_it_throws_when_the_product_is_not_found(): void
    {
        $id = (string) new Ulid();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage($id))
            ->willReturn(null);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductUpdater($repository);

        $this->expectException(ProductNotFoundException::class);

        $useCase(new UpdateProductDto($id, name: 'New'));
    }

    public function test_it_updates_and_saves_the_product_when_found(): void
    {
        $product = $this->createMock(Product::class);
        $product->expects($this->once())
            ->method('rename')
            ->with(ProductName::fromStorage('New Name'))
            ->willReturnSelf();
        $product->expects($this->once())
            ->method('changePrice')
            ->with(ProductPrice::fromStorage(99.99))
            ->willReturnSelf();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage('01HZZZZZZZZZZZZZZZZZZZZZZZ'))
            ->willReturn($product);
        $repository->expects($this->once())->method('save')->with($product);

        $useCase = new ProductUpdater($repository);

        $useCase(new UpdateProductDto('01HZZZZZZZZZZZZZZZZZZZZZZZ', 'New Name', 99.99));
    }

    public function test_it_only_touches_the_fields_that_were_sent(): void
    {
        $product = $this->createMock(Product::class);
        $product->expects($this->once())->method('rename')->willReturnSelf();
        $product->expects($this->never())->method('changePrice');

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('find')->willReturn($product);
        $repository->expects($this->once())->method('save');

        $useCase = new ProductUpdater($repository);

        $useCase(new UpdateProductDto('01HZZZZZZZZZZZZZZZZZZZZZZZ', name: 'New Name'));
    }

    public function test_it_rejects_an_empty_name(): void
    {
        $product = $this->createStub(Product::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('find')->willReturn($product);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductUpdater($repository);

        $this->expectException(InvalidArgumentException::class);
        $useCase(new UpdateProductDto('01HZZZZZZZZZZZZZZZZZZZZZZZ', name: '   '));
    }
}