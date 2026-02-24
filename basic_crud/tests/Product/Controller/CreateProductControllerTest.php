<?php

declare(strict_types=1);

namespace Tests\Product\Controller;

use App\Product\Controller\CreateProductController;
use App\Product\Entity\Product;
use App\Product\UseCase\CreateProduct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateProductControllerTest extends TestCase
{
    private CreateProduct $createProduct;
    private CreateProductController $controller;

    protected function setUp(): void
    {
        $this->createProduct = $this->createMock(CreateProduct::class);
        $this->controller = new CreateProductController($this->createProduct);
    }

    public function test_it_creates_product(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('id')->willReturn('01HXK5Z3V8N7MJCP2E4G1QRST6');
        $product->method('name')->willReturn('New Product');
        $product->method('price')->willReturn(149.99);

        $this->createProduct
            ->method('__invoke')
            ->willReturn($product);

        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'New Product',
            'price' => 149.99,
        ]));

        $response = ($this->controller)($request);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertEquals('New Product', $data['name']);
    }

    public function test_it_returns_400_on_invalid_json(): void
    {
        $request = new Request([], [], [], [], [], [], 'invalid-json{');

        $response = ($this->controller)($request);

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Invalid JSON provided', $data['message']);
    }

    public function test_it_returns_400_on_validation_error(): void
    {
        $this->createProduct
            ->method('__invoke')
            ->willThrowException(new InvalidArgumentException('Name is required'));

        $request = new Request([], [], [], [], [], [], json_encode(['price' => 99.99]));

        $response = ($this->controller)($request);

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Validation error', $data['message']);
    }
}