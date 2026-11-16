<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Http\Controller;

use App\Product\Application\ProductByNameFinder\ProductByNameFinder;
use App\Product\Infrastructure\Http\Presenter\FindProductsByNamePresenter;
use App\Shared\Infrastructure\Http\ApiResponse;
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

            return ApiResponse::collection(FindProductsByNamePresenter::present($products));
        } catch (Throwable $e) {
            return ApiResponse::serverError('product_retrieval_failed', $e->getMessage());
        }
    }
}