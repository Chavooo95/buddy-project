<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\DeleteProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{id}', name: 'product_delete', methods: ['DELETE'])]
final class DeleteProductController
{
    private DeleteProduct $deleteProduct;

    public function __construct(DeleteProduct $deleteProduct)
    {
        $this->deleteProduct = $deleteProduct;
    }

    public function __invoke(string $id): JsonResponse
    {
        try {
            $success = ($this->deleteProduct)($id);

            if (!$success) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } catch (Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}