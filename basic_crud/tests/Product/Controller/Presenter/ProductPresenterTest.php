<?php
declare(strict_types=1);

namespace Test\Product\Controller\Presenter;

use App\Product\Controller\Presenter\ProductPresenter;
use App\Product\Entity\ValueObjects\ProductName;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductPresenterTest extends TestCase
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
            ProductPresenter::present($product),
        );
    }

    public function test_it_includes_updatedAt_once_the_product_changed(): void
    {
        $product = (new ProductBuilder())->build();
        $product->rename(ProductName::fromString('Renamed'));

        $presented = ProductPresenter::present($product);

        $this->assertSame($product->updatedAt()->format(DateTimeInterface::ATOM), $presented['updatedAt']);
    }

    public function test_it_presents_every_product_of_a_collection_in_order(): void
    {
        $first = (new ProductBuilder())->withName('First')->build();
        $second = (new ProductBuilder())->withName('Second')->build();

        $presented = ProductPresenter::presentCollection([$first, $second]);

        $this->assertSame(
            [ProductPresenter::present($first), ProductPresenter::present($second)],
            $presented,
        );
    }

    public function test_it_presents_an_empty_collection_as_an_empty_list(): void
    {
        $this->assertSame([], ProductPresenter::presentCollection([]));
    }
}
