<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ProductShower;
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
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'ulid' => $product->id(),
                'name' => $product->name()?->value,
                'price' => $product->price()?->value,
            ]);
        } catch (Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error retrieving product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}