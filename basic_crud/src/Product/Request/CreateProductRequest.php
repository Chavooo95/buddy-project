<?php
declare(strict_types=1);

namespace App\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateProductRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Product name is required')]
        #[Assert\Type(type: 'string', message: 'Product name must be a string')]
        public readonly mixed $name,

        #[Assert\NotNull(message: 'Product price is required')]
        public readonly mixed $price,
    ) {
    }
}