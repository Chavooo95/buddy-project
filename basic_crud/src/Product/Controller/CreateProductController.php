<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Request\CreateProductRequest;
use App\Product\UseCase\ProductCreator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Invalid JSON provided',
                ], 400);
            }

            $violations = $this->validator->validate($data, CreateProductRequest::constraints());

            if (count($violations) > 0) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Validation error',
                    'error' => $this->firstError($violations),
                ], 400);
            }

            $product = ($this->createProduct)(CreateProductRequest::fromArray($data));

            $response = new JsonResponse(status: 201);
            $response->setEncodingOptions($response->getEncodingOptions() | \JSON_PRESERVE_ZERO_FRACTION);
            $response->setData([
                'success' => true,
                'message' => 'Product created successfully',
                'ulid' => $product->id()->value,
                'name' => $product->name()->value,
                'price' => $product->price()->value,
            ]);
            return $response;
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

    private function firstError(ConstraintViolationListInterface $violations): string
    {
        $violation = $violations->get(0);
        $field = trim($violation->getPropertyPath(), '[]');

        return $field === ''
            ? (string) $violation->getMessage()
            : sprintf('%s: %s', $field, $violation->getMessage());
    }
}