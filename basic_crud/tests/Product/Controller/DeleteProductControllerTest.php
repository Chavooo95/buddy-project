<?php

declare(strict_types=1);

namespace Tests\Product\Controller;

use App\Product\Controller\DeleteProductController;
use App\Product\UseCase\DeleteProduct;
use PHPUnit\Framework\TestCase;

class DeleteProductControllerTest extends TestCase
{
    private DeleteProduct $deleteProduct;
    private DeleteProductController $controller;

    protected function setUp(): void
    {
        $this->deleteProduct = $this->createMock(DeleteProduct::class);
        $this->controller = new DeleteProductController($this->deleteProduct);
    }

    public function test_it_deletes_product(): void
    {
        $this->deleteProduct
            ->method('__invoke')
            ->with('01HXK5Z3V8N7MJCP2E4G1QRST6')
            ->willReturn(true);

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6');

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Product deleted successfully', $data['message']);
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $this->deleteProduct
            ->method('__invoke')
            ->willReturn(false);

        $response = ($this->controller)('nonexistent-id');

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
    }

    public function test_it_returns_error_on_exception(): void
    {
        $this->deleteProduct
            ->method('__invoke')
            ->willThrowException(new \RuntimeException('Database error'));

        $response = ($this->controller)('01HXK5Z3V8N7MJCP2E4G1QRST6');

        $this->assertEquals(500, $response->getStatusCode());
    }
}
