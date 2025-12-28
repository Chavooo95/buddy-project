<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[] = $product->toArray();
        }

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json($product->toArray());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['price'])) {
            return $this->json(['message' => 'Missing parameters'], 400);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice((float) $data['price']);

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($product->toArray(), 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (isset($data['price'])) {
            $product->setPrice((float) $data['price']);
        }

        $entityManager->flush();

        return $this->json($product->toArray());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product deleted successfully']);
    }
}