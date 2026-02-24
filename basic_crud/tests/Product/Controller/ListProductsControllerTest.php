<?php

declare(strict_types=1);

namespace Test\Product\Controller;

use App\Product\Controller\ListProductsController;
use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use App\Product\UseCase\ListProducts;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;


class ListProductsControllerTest extends TestCase
{
    private ListProducts $listProducts;
    private ListProductsController $controller;

    protected function setUp(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $this->listProducts = new ListProducts($repository);
        $this->controller = new ListProductsController($this->listProducts);
    }

    public function test_it_lists_all_products(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('toArray')->willReturn([
            'id' => '01HXK5Z3V8N7MJCP2E4G1QRST6',
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        $this->listProducts
            ->method('__invoke')
            ->with(null)
            ->willReturn([$product]);

        $request = new Request();
        $response = ($this->controller)($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['data']);
    }

    public function test_it_lists_products_with_search_filter(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('toArray')->willReturn([
            'id' => '01HXK5Z3V8N7MJCP2E4G1QRST6',
            'name' => 'Laptop',
            'price' => 999.99,
        ]);

        $this->listProducts
            ->method('__invoke')
            ->with('Laptop')
            ->willReturn([$product]);

        $request = new Request(['search' => 'Laptop']);
        $response = ($this->controller)($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
    }

    public function test_it_returns_error_on_exception(): void
    {
        $this->listProducts
            ->method('__invoke')
            ->willThrowException(new \RuntimeException('Database error'));

        $request = new Request();
        $response = ($this->controller)($request);

        $this->assertEquals(500, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
    }
}