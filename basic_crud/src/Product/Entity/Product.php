<?php
declare(strict_types=1);

namespace App\Product\Entity;

use App\Product\Entity\ValueObjects\ProductName;
use App\Product\Entity\ValueObjects\ProductPrice;
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
    private ?ProductName $name = null;
    private ?ProductPrice $price = null;
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

    public function name(): ?ProductName
    {
        return $this->name;
    }

    public function setName(ProductName $name): static
    {
        $this->name = $name;
        $this->updateTimestamp();

        return $this;
    }

    public function price(): ?ProductPrice
    {
        return $this->price;
    }

    public function setPrice(ProductPrice $price): static
    {
        $this->price = $price;
        $this->updateTimestamp();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name?->value,
            'price'     => $this->price?->value,
            'createdAt' => $this->createdAt?->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(\DateTimeInterface::ATOM),
        ];
    }

    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
