<?php
declare(strict_types=1);

namespace App\Product\Request\Constraint;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductConstraints
{
    public static function collection(): Assert\Collection
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
}