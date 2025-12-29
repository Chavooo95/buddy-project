<?php

namespace App\Controller;

use App\Service\ProductServiceFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductServiceFactory $productServiceFactory
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $databaseType = $this->getDatabaseTypeFromRequest($request);
        $productService = $this->productServiceFactory->getProductService($databaseType);
        
        $products = $productService->findAll();

        return $this->json([
            'database_type' => $databaseType ?? $this->productServiceFactory->getDatabaseType(),
            'data' => $products
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(string $id, Request $request): JsonResponse
    {
        $databaseType = $this->getDatabaseTypeFromRequest($request);
        $productService = $this->productServiceFactory->getProductService($databaseType);
        
        $product = $productService->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json([
            'database_type' => $databaseType ?? $this->productServiceFactory->getDatabaseType(),
            'data' => $product
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['price'])) {
            return $this->json(['message' => 'Missing parameters'], 400);
        }

        $databaseType = $this->getDatabaseTypeFromRequest($request);
        $productService = $this->productServiceFactory->getProductService($databaseType);

        $product = $productService->create($data);

        return $this->json([
            'database_type' => $databaseType ?? $this->productServiceFactory->getDatabaseType(),
            'data' => $product
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $databaseType = $this->getDatabaseTypeFromRequest($request);
        $productService = $this->productServiceFactory->getProductService($databaseType);

        $data = json_decode($request->getContent(), true);

        $product = $productService->update($id, $data);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json([
            'database_type' => $databaseType ?? $this->productServiceFactory->getDatabaseType(),
            'data' => $product
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(string $id, Request $request): JsonResponse
    {
        $databaseType = $this->getDatabaseTypeFromRequest($request);
        $productService = $this->productServiceFactory->getProductService($databaseType);

        if (!$productService->delete($id)) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json([
            'database_type' => $databaseType ?? $this->productServiceFactory->getDatabaseType(),
            'message' => 'Product deleted successfully'
        ]);
    }

    private function getDatabaseTypeFromRequest(Request $request): ?string
    {
        // Check for database type in query parameter
        $dbType = $request->query->get('db_type');
        if ($dbType) {
            return $dbType;
        }

        // Check for database type in header
        $dbType = $request->headers->get('X-Database-Type');
        if ($dbType) {
            return $dbType;
        }

        return null;
    }
}