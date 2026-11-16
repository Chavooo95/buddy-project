<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Controller;

use App\Product\Application\ProductDeleter\ProductDeleter;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Shared\Infrastructure\Http\ApiResponse;
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
            ($this->deleteProduct)($id);

            return ApiResponse::noContent();
        } catch (ProductNotFoundException) {
            return ApiResponse::notFound('Product not found');
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('product_deletion_failed', $e->getMessage());
        }
    }
}