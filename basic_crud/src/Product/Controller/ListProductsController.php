<?php

declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\UseCase\ListProducts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products', name: 'product_list', methods: ['GET'])]
final class ListProductsController extends AbstractController
{
    private ListProducts $listProducts;

    public function __construct(ListProducts $listProducts)
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

            return $this->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error retrieving products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}