<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ProductDeleter;
use App\Shared\Http\ApiResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/{id}', name: 'product_delete', methods: ['DELETE'])]
final class DeleteProductController
{
    private ProductDeleter $deleteProduct;

    public function __construct(ProductDeleter $deleteProduct)
    {
        $this->deleteProduct = $deleteProduct;
    }

    public function __invoke(string $id): JsonResponse
    {
        try {
            $success = ($this->deleteProduct)($id);

            if (!$success) {
                return ApiResponse::notFound('Product not found');
            }

            return ApiResponse::ok(['message' => 'Product deleted successfully']);
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('Error deleting product', $e->getMessage());
        }
    }
}