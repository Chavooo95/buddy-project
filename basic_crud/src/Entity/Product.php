<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use DateTime;

/**
 * Product Entity
 * Configured through XML mapping for both ORM and ODM
 */
class Product
{
    private mixed $id = null;
    private ?string $name = null;
    private ?float $price = null;
    private ?DateTimeInterface $createdAt = null;
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        $this->updateTimestamp();

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        $this->updateTimestamp();

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
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
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}