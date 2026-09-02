<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Request\CreateProductRequest;
use App\Product\UseCase\ProductCreator;
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

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Invalid JSON provided',
                ], 400);
            }

            $violations = $this->validator->validate(new CreateProductRequest(
                $data['name'] ?? null,
                $data['price'] ?? null,
            ));

            if (count($violations) > 0) {
                throw new InvalidArgumentException($violations->get(0)->getMessage());
            }

            $product = ($this->createProduct)($data);

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
}