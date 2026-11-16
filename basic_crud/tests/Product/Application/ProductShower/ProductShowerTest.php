<?php
declare(strict_types=1);

namespace Test\Product\Application\ProductShower;

use App\Product\Application\ProductShower\ProductShower;
use App\Product\Application\Responses\ProductResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductShowerTest extends TestCase
{
    public function test_it_returns_null_when_the_product_is_not_found(): void
    {
        $id = (string) new Ulid();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage($id))
            ->willReturn(null);

        $useCase = new ProductShower($repository);

        $this->assertNull($useCase($id));
    }

    public function test_it_returns_the_product_when_found(): void
    {
        $product = (new ProductBuilder())->build();

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('find')
            ->with(ProductId::fromStorage('01HZZZZZZZZZZZZZZZZZZZZZZZ'))
            ->willReturn($product);

        $useCase = new ProductShower($repository);

        $this->assertEquals(ProductResponse::fromProduct($product), $useCase('01HZZZZZZZZZZZZZZZZZZZZZZZ'));
    }
}
