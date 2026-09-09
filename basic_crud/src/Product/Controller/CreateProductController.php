<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Request\CreateProductRequest;
use App\Product\UseCase\ProductCreator;
use App\Shared\Http\ApiResponse;
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

            $violations = $this->validator->validate($data, CreateProductRequest::constraints());

            if (count($violations) > 0) {
                return ApiResponse::invalidPayload($violations);
            }

            $product = ($this->createProduct)(CreateProductRequest::fromArray($data));

            return ApiResponse::created([
                'message' => 'Product created successfully',
                'ulid' => $product->id()->value,
                'name' => $product->name()->value,
                'price' => $product->price()->value,
            ]);
        } catch (InvalidArgumentException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (Throwable $e) {
            return ApiResponse::serverError('Error creating product', $e->getMessage());
        }
    }
}
