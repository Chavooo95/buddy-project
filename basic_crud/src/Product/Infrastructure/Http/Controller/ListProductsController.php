<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Controller;

use App\Product\Application\ProductLister\ProductLister;
use App\Shared\Infrastructure\Http\ApiResponse;
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

            return ApiResponse::collection($products->toArray());
        } catch (Throwable $e) {
            return ApiResponse::serverError('product_retrieval_failed', $e->getMessage());
        }
    }
}
