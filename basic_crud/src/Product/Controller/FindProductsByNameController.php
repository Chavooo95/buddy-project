<?php

declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ProductByNameFinder;
use App\Shared\Http\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products/name/{name}', name: 'product_find_by_name', methods: ['GET'])]
final class FindProductsByNameController
{
    private ProductByNameFinder $findProductsByName;

    public function __construct(ProductByNameFinder $findProductsByName)
    {
        $this->findProductsByName = $findProductsByName;
    }

    public function __invoke(string $name): JsonResponse
    {
        try {
            $products = ($this->findProductsByName)($name);

            $data = [];
            foreach ($products as $product) {
                $data[] = $product->toArray();
            }

            return ApiResponse::ok([
                'data' => $data,
                'count' => count($data),
            ]);
        } catch (Throwable $e) {
            return ApiResponse::serverError('Error retrieving products', $e->getMessage());
        }
    }
}