<?php
declare(strict_types=1);

namespace App\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductRequest
{
    public function __construct(
        #[Assert\Type(type: 'string', message: 'Product name must be a string')]
        public readonly mixed $name = null,

        public readonly mixed $price = null,
    ) {
    }
}