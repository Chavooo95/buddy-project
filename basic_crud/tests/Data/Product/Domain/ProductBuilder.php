<?php

declare(strict_types=1);

namespace Test\Data\Product\Domain;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use DateTime;

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
        return new Product(
            ProductId::generate(),
            new ProductName($this->name),
            new ProductPrice($this->price),
            new DateTime(),
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
