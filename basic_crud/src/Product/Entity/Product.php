<?php
declare(strict_types=1);

namespace App\Product\Entity;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;

/**
 * Product Entity
 * Configured through XML mapping for both ORM and ODM
 */
class Product
{
    private $id;
    private string $productId;
    private ?string $name = null;
    private ?float $price = null;
    private ?DateTimeInterface $createdAt = null;
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->productId = (string) new Ulid();
        $this->createdAt = new DateTime();
    }

    public function id(): mixed
    {
        return $this->id;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        $this->updateTimestamp();

        return $this;
    }

    public function price(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        $this->updateTimestamp();

        return $this;
    }

    public function createdAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function updatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'productId' => $this->productId(),
            'name' => $this->name(),
            'price' => $this->price(),
            'createdAt' => $this->createdAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}