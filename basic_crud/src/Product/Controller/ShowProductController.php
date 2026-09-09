<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ProductShower;
use App\Shared\Http\ApiResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{id}', name: 'product_show', methods: ['GET'])]
final class ShowProductController
{
    private ProductShower $showProduct;

    public function __construct(ProductShower $showProduct)
    {
        $this->showProduct = $showProduct;
    }

    public function __invoke(string $id): JsonResponse
    {
        try {
            $product = ($this->showProduct)($id);

            if (!$product) {
                return ApiResponse::notFound('Product not found');
            }

            return ApiResponse::ok([
                'ulid' => $product->id()->value,
                'name' => $product->name()->value,
                'price' => $product->price()->value,
            ]);
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('Error retrieving product', $e->getMessage());
        }
    }
}
