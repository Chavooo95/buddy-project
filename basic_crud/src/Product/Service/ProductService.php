<?php
declare(strict_types=1);

namespace App\Product\Service;

use App\Product\Entity\Product;
use App\Product\Interface\ProductRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * Product Service
 * Handles business logic for Product operations
 */
class ProductService
{
    private ProductRepositoryInterface $productRepository;
    private ManagerRegistry $managerRegistry;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ManagerRegistry $managerRegistry
    ) {
        $this->productRepository = $productRepository;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Get all products
     * 
     * @return Product[]
     */
    public function getAllProducts(): array
    {
        $products = $this->productRepository->findAll();
        return is_array($products) ? $products : [];
    }

    /**
     * Get product by ID
     */
    public function getProductById(string $id): ?Product
    {
        $product = $this->productRepository->find($id);
        return $product instanceof Product ? $product : null;
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        $this->validateProductData($data);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice((float) $data['price']);

        $this->saveProduct($product);

        return $product;
    }

    /**
     * Update an existing product
     */
    public function updateProduct(mixed $id, array $data): ?Product
    {
        $product = $this->getProductById($id);

        if (!$product) {
            return null;
        }

        if (isset($data['name'])) {
            if (empty(trim($data['name']))) {
                throw new InvalidArgumentException('Product name cannot be empty');
            }
            $product->setName($data['name']);
        }

        if (isset($data['price'])) {
            $price = (float) $data['price'];
            if ($price < 0) {
                throw new InvalidArgumentException('Product price cannot be negative');
            }
            $product->setPrice($price);
        }

        $this->saveProduct($product);

        return $product;
    }

    /**
     * Delete a product
     */
    public function deleteProduct(mixed $id): bool
    {
        $product = $this->getProductById($id);

        if (!$product) {
            return false;
        }

        $this->removeProduct($product);
        return true;
    }

    /**
     * Search products by name
     */
    public function searchProductsByName(string $name): array
    {
        return $this->productRepository->findByName($name);
    }

    /**
     * Get products by price range
     */
    public function getProductsByPriceRange(float $minPrice, float $maxPrice): array
    {
        if ($minPrice < 0 || $maxPrice < 0) {
            throw new InvalidArgumentException('Price range values cannot be negative');
        }

        if ($minPrice > $maxPrice) {
            throw new InvalidArgumentException('Minimum price cannot be greater than maximum price');
        }

        return $this->productRepository->findByPriceRange($minPrice, $maxPrice);
    }

    /**
     * Get products created after a specific date
     */
    public function getProductsCreatedAfter(\DateTimeInterface $date): array
    {
        return $this->productRepository->findCreatedAfter($date);
    }

    /**
     * Validate product data
     */
    private function validateProductData(array $data): void
    {
        if (!isset($data['name']) || empty(trim($data['name']))) {
            throw new InvalidArgumentException('Product name is required');
        }

        if (!isset($data['price'])) {
            throw new InvalidArgumentException('Product price is required');
        }

        $price = (float) $data['price'];
        if ($price < 0) {
            throw new InvalidArgumentException('Product price cannot be negative');
        }
    }

    /**
     * Save product to database
     */
    private function saveProduct(Product $product): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }

    /**
     * Remove product from database
     */
    private function removeProduct(Product $product): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
    }
}