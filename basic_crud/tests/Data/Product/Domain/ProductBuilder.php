<?php

declare(strict_types=1);

namespace Test\Data\Product\Domain;

use App\Product\Entity\Product;

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
        return (new Product())
            ->setName($this->name)
            ->setPrice($this->price);
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
