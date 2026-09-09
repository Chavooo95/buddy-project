<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Request\UpdateProductRequest;
use App\Product\UseCase\ProductUpdater;
use App\Shared\Http\ApiResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[Route('/api/products/{id}', name: 'product_update', methods: ['PUT'])]
final class UpdateProductController
{
    private ProductUpdater $updateProduct;
    private ValidatorInterface $validator;

    public function __construct(ProductUpdater $updateProduct, ValidatorInterface $validator)
    {
        $this->updateProduct = $updateProduct;
        $this->validator = $validator;
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return ApiResponse::invalidJson();
            }

            $violations = $this->validator->validate($data, UpdateProductRequest::constraints());

            if (count($violations) > 0) {
                return ApiResponse::invalidPayload($violations);
            }

            $product = ($this->updateProduct)($id, UpdateProductRequest::fromArray($data));

            if (!$product) {
                return ApiResponse::notFound('Product not found');
            }

            return ApiResponse::ok([
                'message' => 'Product updated successfully',
                'ulid' => $product->id()->value,
                'name' => $product->name()->value,
                'price' => $product->price()->value,
            ]);
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('Error updating product', $e->getMessage());
        }
    }
}
