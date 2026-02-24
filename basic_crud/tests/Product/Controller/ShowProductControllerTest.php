<?php

declare(strict_types=1);

namespace Tests\Product\Controller;

use App\Product\Controller\ShowProductController;
use App\Product\Entity\Product;
use App\Product\UseCase\ShowProduct;
use PHPUnit\Framework\TestCase;

class ShowProductControllerTest extends TestCase
{
    private ShowProduct $showProduct;
    private ShowProductController $controller;

    protected function setUp(): void
    {
        $this->showProduct = $this->createMock(ShowProduct::class);
        $this->controller = new ShowProductController($this->showProduct);
    }

    public function test_it_shows_product_by_id(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('id')->willReturn('01HXK5Z3V8N7MJCP2E4G1QRST6');
        $product->method('name')->willReturn('Test Product');
        $product->method('price')->willReturn(99.99);

        $this->showProduct
            ->method('__invoke')
            ->with('01HXK5Z3V8N7MJCP2E4G1QRST6')
            ->willReturn($product);

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6');

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Test Product', $data['name']);
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $this->showProduct
            ->method('__invoke')
            ->willReturn(null);

        $response = ($this->controller)('nonexistent-id');

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
    }

    public function test_it_returns_error_on_exception(): void
    {
        $this->showProduct
            ->method('__invoke')
            ->willThrowException(new \RuntimeException('Database error'));

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6');

        $this->assertEquals(500, $response->getStatusCode());
    }
}