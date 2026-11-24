<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Controller;

use App\Product\Application\ProductUpdater\ProductUpdater;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Infrastructure\Http\Request\Constraint\UpdateProductConstraints;
use App\Product\Infrastructure\Http\Request\UpdateProductRequest;
use App\Shared\Infrastructure\Http\ApiResponse;
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

            $violations = $this->validator->validate($data, UpdateProductConstraints::collection());

            if (count($violations) > 0) {
                return ApiResponse::invalidPayload($violations);
            }

            ($this->updateProduct)(UpdateProductRequest::fromArray($data)->toDto($id));

            return ApiResponse::noContent();
        } catch (ProductNotFoundException) {
            return ApiResponse::notFound('Product not found');
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('product_update_failed', $e->getMessage());
        }
    }
}
