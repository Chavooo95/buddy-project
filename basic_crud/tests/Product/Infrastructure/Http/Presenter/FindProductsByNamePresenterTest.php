<?php
declare(strict_types=1);

namespace Test\Product\Infrastructure\Http\Presenter;

use App\Product\Application\Responses\ProductsResponse;
use App\Product\Infrastructure\Http\Presenter\FindProductsByNamePresenter;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class FindProductsByNamePresenterTest extends TestCase
{
    public function test_it_presents_every_matching_product_in_order(): void
    {
        $first = (new ProductBuilder())->withName('Keyboard')->withPrice(1.0)->build();
        $second = (new ProductBuilder())->withName('Keyboard')->withPrice(2.0)->build();

        $this->assertSame(
            [
                [
                    'id'        => $first->id()->value,
                    'name'      => 'Keyboard',
                    'price'     => 1.0,
                    'createdAt' => $first->createdAt()->format(DateTimeInterface::ATOM),
                    'updatedAt' => null,
                ],
                [
                    'id'        => $second->id()->value,
                    'name'      => 'Keyboard',
                    'price'     => 2.0,
                    'createdAt' => $second->createdAt()->format(DateTimeInterface::ATOM),
                    'updatedAt' => null,
                ],
            ],
            FindProductsByNamePresenter::present(ProductsResponse::fromProducts([$first, $second])),
        );
    }

    public function test_it_presents_no_matches_as_an_empty_list(): void
    {
        $this->assertSame([], FindProductsByNamePresenter::present(ProductsResponse::fromProducts([])));
    }
}
