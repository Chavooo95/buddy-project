<?php
declare(strict_types=1);

namespace App\Product\Entity;

use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
use DateTime;
use DateTimeInterface;

/**
 * Product Entity
 * Configured through XML mapping for both ORM and ODM
 */
class Product
{
    private ProductId $id;
    private ProductName $name;
    private ProductPrice $price;
    private DateTimeInterface $createdAt;
    private ?DateTimeInterface $updatedAt = null;

    private function __construct(ProductId $id, ProductName $name, ProductPrice $price, DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->createdAt = $createdAt;
    }

    public static function create(ProductId $id, ProductName $name, ProductPrice $price): self
    {
        return new self($id, $name, $price, new DateTime());
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function rename(ProductName $name): static
    {
        $this->name = $name;
        $this->updateTimestamp();

        return $this;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function changePrice(ProductPrice $price): static
    {
        $this->price = $price;
        $this->updateTimestamp();

        return $this;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
