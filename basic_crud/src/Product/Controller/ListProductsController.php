<?php

declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ProductLister;
use App\Shared\Http\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products', name: 'product_list', methods: ['GET'])]
final class ListProductsController
{
    private ProductLister $listProducts;

    public function __construct(ProductLister $listProducts)
    {
        $this->listProducts = $listProducts;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            $products = ($this->listProducts)($search);

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
