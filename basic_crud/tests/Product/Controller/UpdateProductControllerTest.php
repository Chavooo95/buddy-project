<?php

declare(strict_types=1);

namespace Tests\Product\Controller;

use App\Product\Controller\UpdateProductController;
use App\Product\Entity\Product;
use App\Product\UseCase\UpdateProduct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateProductControllerTest extends TestCase
{
    private UpdateProduct $updateProduct;
    private UpdateProductController $controller;

    protected function setUp(): void
    {
        $this->updateProduct = $this->createMock(UpdateProduct::class);
        $this->controller = new UpdateProductController($this->updateProduct);
    }

    public function test_it_updates_product(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('id')->willReturn('01HXK5Z3V8N7MJCP2E4G1QRST6');
        $product->method('name')->willReturn('Updated Product');
        $product->method('price')->willReturn(199.99);

        $this->updateProduct
            ->method('__invoke')
            ->willReturn($product);

        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'Updated Product',
            'price' => 199.99,
        ]));

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6', $request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Updated Product', $data['name']);
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $this->updateProduct
            ->method('__invoke')
            ->willReturn(null);

        $request = new Request([], [], [], [], [], [], json_encode(['name' => 'Test']));

        $response = ($this->controller)('nonexistent-id', $request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_returns_400_on_invalid_json(): void
    {
        $request = new Request([], [], [], [], [], [], 'invalid{json');

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6', $request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_it_returns_400_on_validation_error(): void
    {
        $this->updateProduct
            ->method('__invoke')
            ->willThrowException(new InvalidArgumentException('Price must be positive'));

        $request = new Request([], [], [], [], [], [], json_encode(['price' => -10]));

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6', $request);

        $this->assertEquals(400, $response->getStatusCode());
    }
}