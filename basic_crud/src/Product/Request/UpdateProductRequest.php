<?php
declare(strict_types=1);

namespace App\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateProductRequest
{
    public function __construct(
        public ?string $name = null,
        public ?float $price = null,
    ) {
    }

    /**
     * Both fields are optional, but an explicit null is rejected: null here
     * means "not sent", never "clear the value".
     */
    public static function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            fields: [
                'name' => new Assert\Optional([
                    new Assert\NotBlank(message: 'must not be blank'),
                    new Assert\Type(type: 'string', message: 'must be a string'),
                ]),
                'price' => new Assert\Optional([
                    new Assert\NotNull(message: 'must not be null'),
                    new Assert\Type(type: 'numeric', message: 'must be numeric'),
                ]),
            ],
            extraFieldsMessage: 'This field was not expected',
        );
    }

    /** @param array<string, mixed> $data payload already validated against self::constraints() */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['name']) ? (string) $data['name'] : null,
            isset($data['price']) ? (float) $data['price'] : null,
        );
    }
}