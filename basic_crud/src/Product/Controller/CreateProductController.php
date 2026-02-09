<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\CreateProduct;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products', name: 'product_create', methods: ['POST'])]
final class CreateProductController
{
    private CreateProduct $createProduct;

    public function __construct(CreateProduct $createProduct)
    {
        $this->createProduct = $createProduct;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Invalid JSON provided',
                ], 400);
            }

            $product = ($this->createProduct)($data);

            return new JsonResponse([
                'success' => true,
                'message' => 'Product created successfully',
                'ULID' => $product->id(),
                'name' => $product->name(),
                'price' => $product->price(),
            ], 201);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation error',
                'error' => $e->getMessage(),
            ], 400);
        } catch (Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}