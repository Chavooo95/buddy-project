<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Request\UpdateProductRequest;
use App\Product\UseCase\ProductUpdater;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Invalid JSON provided',
                ], 400);
            }

            $violations = $this->validator->validate($data, UpdateProductRequest::constraints());

            if (count($violations) > 0) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Validation error',
                    'error' => $this->firstError($violations),
                ], 400);
            }

            $product = ($this->updateProduct)($id, UpdateProductRequest::fromArray($data));

            if (!$product) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'Product updated successfully',
                'ulid' => $product->id()->value,
                'name' => $product->name()->value,
                'price' => $product->price()->value,
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

    private function firstError(ConstraintViolationListInterface $violations): string
    {
        $violation = $violations->get(0);
        $field = trim($violation->getPropertyPath(), '[]');

        return $field === ''
            ? (string) $violation->getMessage()
            : sprintf('%s: %s', $field, $violation->getMessage());
    }
}