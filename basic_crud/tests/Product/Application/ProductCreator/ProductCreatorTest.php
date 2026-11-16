<?php
declare(strict_types=1);

namespace Test\Product\Application\ProductCreator;

use App\Product\Application\ProductCreator\ProductCreator;
use App\Product\Application\Responses\ProductResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Infrastructure\Http\Request\CreateProductRequest;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Infrastructure\Repository\InMemoryProductRepository;

final class ProductCreatorTest extends TestCase
{
    public function test_it_creates_and_saves_a_product(): void
    {
        $repository = new InMemoryProductRepository();
        $useCase = new ProductCreator($repository);

        $response = $useCase(CreateProductRequest::fromArray(['name' => 'Keyboard', 'price' => 12.34]));

        $saved = $repository->findByName('Keyboard')[0];
        $this->assertEquals(ProductResponse::fromProduct($saved), $response);
        $this->assertSame('Keyboard', $response->name);
        $this->assertSame(12.34, $response->price);
    }

    public function test_it_rejects_a_negative_price(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->never())->method('save');

        $useCase = new ProductCreator($repository);

        $this->expectException(InvalidArgumentException::class);
        $useCase(CreateProductRequest::fromArray(['name' => 'Keyboard', 'price' => -1.0]));
    }
}