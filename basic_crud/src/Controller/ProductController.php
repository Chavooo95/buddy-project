<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Product Controller
 * Handles HTTP requests for Product operations
 */
#[Route('/api/products')]
class ProductController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products
     */
    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->query->get('search');
            
            if ($search) {
                $products = $this->productService->searchProductsByName($search);
            } else {
                $products = $this->productService->getAllProducts();
            }

            $data = [];
            foreach ($products as $product) {
                $data[] = $product->toArray();
            }

            return $this->json([
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error retrieving products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product by ID
     */
    #[Route('/{id}', methods: ['GET'])]
    public function show(mixed $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return $this->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return $this->json([
                'success' => true,
                'data' => $product->toArray()
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error retrieving product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new product
     */
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid JSON provided'
                ], 400);
            }

            $product = $this->productService->createProduct($data);

            return $this->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->toArray()
            ], 201);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     */
    #[Route('/{id}', methods: ['PUT'])]
    public function update(mixed $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid JSON provided'
                ], 400);
            }

            $product = $this->productService->updateProduct($id, $data);

            if (!$product) {
                return $this->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return $this->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(mixed $id): JsonResponse
    {
        try {
            $success = $this->productService->deleteProduct($id);

            if (!$success) {
                return $this->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return $this->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}