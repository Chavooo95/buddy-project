<?php
declare(strict_types=1);

namespace Test\Product\Infrastructure\Http\Presenter;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Infrastructure\Http\Presenter\CreateProductPresenter;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class CreateProductPresenterTest extends TestCase
{
    public function test_it_presents_the_created_product_with_the_public_api_shape(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->withPrice(12.5)->build();

        $this->assertSame(
            [
                'id'        => $product->id()->value,
                'name'      => 'Keyboard',
                'price'     => 12.5,
                'createdAt' => $product->createdAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => null,
            ],
            CreateProductPresenter::present(ProductResponse::fromProduct($product)),
        );
    }
}
