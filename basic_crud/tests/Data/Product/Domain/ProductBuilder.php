<?php

declare(strict_types=1);

namespace Test\Data\Product\Domain;

use App\Product\Domain\Product;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;

final class ProductBuilder
{
    private string $name;
    private float $price;

    public function __construct()
    {
        $this->name = 'Test Product';
        $this->price = 99.99;
    }

    public function build(): Product
    {
        return Product::create(
            ProductId::generate(),
            ProductName::fromStorage($this->name),
            ProductPrice::fromStorage($this->price),
        );
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
