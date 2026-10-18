<?php
declare(strict_types=1);

namespace App\Product\Entity\ValueObjects;

use App\Product\Entity\ValueObjects\Exception\ProductIdException;
use Symfony\Component\Uid\Ulid;

final readonly class ProductId
{
    public string $value;

    /**
     * Without a value a brand-new ULID is generated; with one, the incoming
     * value is verified before being accepted.
     */
    private function __construct(?string $value = null)
    {
        if ($value === null) {
            $this->value = (string) new Ulid();
            return;
        }

        $value = trim($value);
        if ($value === '') {
            throw ProductIdException::empty();
        }
        if (!Ulid::isValid($value)) {
            throw ProductIdException::invalidFormat($value);
        }
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self();
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * Required by the Doctrine ORM: the identity map keys entities by the
     * string form of their identifier, so an object id must be stringable.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
