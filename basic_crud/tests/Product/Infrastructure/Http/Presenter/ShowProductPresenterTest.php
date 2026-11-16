<?php
declare(strict_types=1);

namespace Test\Product\Infrastructure\Http\Presenter;

use App\Product\Application\Responses\ProductResponse;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Infrastructure\Http\Presenter\ShowProductPresenter;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ShowProductPresenterTest extends TestCase
{
    public function test_it_presents_a_product_with_the_public_api_shape(): void
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
            ShowProductPresenter::present(ProductResponse::fromProduct($product)),
        );
    }

    public function test_it_includes_updatedAt_once_the_product_changed(): void
    {
        $product = (new ProductBuilder())->build();
        $product->rename(ProductName::fromStorage('Renamed'));

        $presented = ShowProductPresenter::present(ProductResponse::fromProduct($product));

        $this->assertSame($product->updatedAt()?->format(DateTimeInterface::ATOM), $presented['updatedAt']);
    }
}
