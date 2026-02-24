<?php
declare(strict_types=1);

namespace App\Tests\Product\UseCase;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\CreateProduct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CreateProductTest extends TestCase
{
    public function testCreatesAndSavesProduct(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);

        $repo->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Product::class));

        $uc = new CreateProduct($repo);

        $product = $uc(['name' => 'Keyboard', 'price' => 12.34]);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function testRejectsMissingName(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->never())->method('save');

        $uc = new CreateProduct($repo);

        $this->expectException(InvalidArgumentException::class);
        $uc(['price' => 1]);
    }

    public function testRejectsNegativePrice(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $repo->expects($this->never())->method('save');

        $uc = new CreateProduct($repo);

        $this->expectException(InvalidArgumentException::class);
        $uc(['name' => 'Keyboard', 'price' => -1]);
    }
}
