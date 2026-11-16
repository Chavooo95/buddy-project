<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Controller;

use App\Product\Application\ProductCreator\ProductCreator;
use App\Product\Infrastructure\Http\Presenter\CreateProductPresenter;
use App\Product\Infrastructure\Http\Request\Constraint\CreateProductConstraints;
use App\Product\Infrastructure\Http\Request\CreateProductRequest;
use App\Shared\Infrastructure\Http\ApiResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[Route('/api/products', name: 'product_create', methods: ['POST'])]
final class CreateProductController
{
    private ProductCreator $createProduct;
    private ValidatorInterface $validator;

    public function __construct(ProductCreator $createProduct, ValidatorInterface $validator)
    {
        $this->createProduct = $createProduct;
        $this->validator = $validator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return ApiResponse::invalidJson();
            }

            $violations = $this->validator->validate($data, CreateProductConstraints::collection());

            if (count($violations) > 0) {
                return ApiResponse::invalidPayload($violations);
            }

            $product = ($this->createProduct)(CreateProductRequest::fromArray($data));

            return ApiResponse::created(
                CreateProductPresenter::present($product),
                '/api/products/' . $product->id,
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('product_creation_failed', $e->getMessage());
        }
    }
}
