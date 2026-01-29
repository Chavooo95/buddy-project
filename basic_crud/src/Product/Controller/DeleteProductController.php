<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\DeleteProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{productId}', name: 'product_delete', methods: ['DELETE'])]
final class DeleteProductController extends AbstractController
{
    private DeleteProduct $deleteProduct;

    public function __construct(DeleteProduct $deleteProduct)
    {
        $this->deleteProduct = $deleteProduct;
    }

    public function __invoke(string $productId): JsonResponse
    {
        try {
            $success = ($this->deleteProduct)($productId);

            if (!$success) {
                return $this->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return $this->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}