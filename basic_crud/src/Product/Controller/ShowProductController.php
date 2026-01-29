<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ShowProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{id}', name: 'product_show', methods: ['GET'])]
final class ShowProductController
{
    private ShowProduct $showProduct;

    public function __construct(ShowProduct $showProduct)
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
                'data' => $product->toArray(),
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