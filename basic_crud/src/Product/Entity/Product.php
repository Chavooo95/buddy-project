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
    private string $id;
    private ?string $name = null;
    private ?float $price = null;
    private ?DateTimeInterface $createdAt = null;
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->id = (string) new Ulid();
        $this->createdAt = new DateTime();
    }

    public function id(): string
    {
        return $this->id;
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

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'price'     => $this->price,
            'createdAt' => $this->createdAt?->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(\DateTimeInterface::ATOM),
        ];
    }

    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}