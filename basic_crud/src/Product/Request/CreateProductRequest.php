<?php
declare(strict_types=1);

namespace App\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductRequest
{
    public function __construct(
        public string $name,
        public float $price,
    ) {
    }

    /**
     * Describes the shape of the raw payload, so it can be validated before
     * anything is built out of it.
     */
    public static function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            fields: [
                'name' => [
                    new Assert\NotBlank(message: 'must not be blank'),
                    new Assert\Type(type: 'string', message: 'must be a string'),
                ],
                'price' => [
                    new Assert\NotNull(message: 'must not be null'),
                    new Assert\Type(type: 'numeric', message: 'must be numeric'),
                ],
            ],
            missingFieldsMessage: 'This field is required',
            extraFieldsMessage: 'This field was not expected',
        );
    }

    /** @param array<string, mixed> $data payload already validated against self::constraints() */
    public static function fromArray(array $data): self
    {
        return new self((string) $data['name'], (float) $data['price']);
    }
}