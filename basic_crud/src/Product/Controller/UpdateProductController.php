<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\UpdateProduct;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{productId}', name: 'product_update', methods: ['PUT'])]
final class UpdateProductController
{
    private UpdateProduct $updateProduct;

    public function __construct(UpdateProduct $updateProduct)
    {
        $this->updateProduct = $updateProduct;
    }

    public function __invoke(string $productId, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Invalid JSON provided',
                ], 400);
            }

            $product = ($this->updateProduct)($productId, $data);

            if (!$product) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->toArray(),
            ]);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation error',
                'error' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}