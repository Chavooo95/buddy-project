<?php

declare(strict_types=1);

namespace Test\Integration\Product;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ProductIntegrationTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->clearDatabase();
    }

    abstract protected function clearDatabase(): void;

    protected function createProduct(string $name = 'Test Product', float $price = 99.99): array
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $name, 'price' => $price])
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode(), 'createProduct helper failed: ' . $response->getContent());

        return json_decode($response->getContent(), true);
    }

    // ---- LIST ----

    public function test_list_products_returns_empty_when_no_products(): void
    {
        $this->client->request('GET', '/api/products');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        $this->assertTrue($data['success']);
        $this->assertSame([], $data['data']);
        $this->assertSame(0, $data['count']);
    }

    public function test_list_products_returns_created_products(): void
    {
        $this->createProduct('Keyboard', 49.99);
        $this->createProduct('Mouse', 29.99);

        $this->client->request('GET', '/api/products');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertSame(2, $data['count']);
        $this->assertCount(2, $data['data']);

        $names = array_column($data['data'], 'name');
        $this->assertContains('Keyboard', $names);
        $this->assertContains('Mouse', $names);
    }

    public function test_list_products_response_contains_product_fields(): void
    {
        $this->createProduct('Monitor', 299.99);

        $this->client->request('GET', '/api/products');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $product = $data['data'][0];
        $this->assertArrayHasKey('id', $product);
        $this->assertArrayHasKey('name', $product);
        $this->assertArrayHasKey('price', $product);
        $this->assertArrayHasKey('createdAt', $product);
        $this->assertNotEmpty($product['id']);
        $this->assertSame('Monitor', $product['name']);
        $this->assertSame(299.99, $product['price']);
    }

    public function test_list_products_search_returns_empty_when_no_match(): void
    {
        $this->createProduct('Keyboard', 49.99);

        $this->client->request('GET', '/api/products?search=NonExistentProduct');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertSame(0, $data['count']);
        $this->assertSame([], $data['data']);
    }

    // ---- CREATE ----

    public function test_create_product_returns_201(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Laptop', 'price' => 999.99])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        $this->assertTrue($data['success']);
        $this->assertSame('Laptop', $data['name']);
        $this->assertSame(999.99, $data['price']);
        $this->assertNotEmpty($data['ULID']);
        $this->assertArrayHasKey('message', $data);
    }

    public function test_create_product_fails_when_name_is_missing(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['price' => 99.99])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_create_product_fails_when_name_is_empty(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => '   ', 'price' => 99.99])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_create_product_fails_when_price_is_missing(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Laptop'])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_create_product_fails_with_negative_price(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Laptop', 'price' => -10.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_create_product_allows_zero_price(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Free Item', 'price' => 0.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertSame(0.0, $data['price']);
    }

    public function test_create_product_with_invalid_json_returns_400(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{invalid-json}'
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    // ---- SHOW ----

    public function test_show_product_returns_product(): void
    {
        $created = $this->createProduct('Monitor', 299.99);
        $id = $created['ULID'];

        $this->client->request('GET', "/api/products/{$id}");

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        $this->assertTrue($data['success']);
        $this->assertSame($id, $data['ULID']);
        $this->assertSame('Monitor', $data['name']);
        $this->assertSame(299.99, $data['price']);
    }

    public function test_show_product_returns_404_when_not_found(): void
    {
        $this->client->request('GET', '/api/products/non-existent-id');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertSame('Product not found', $data['message']);
    }

    // ---- UPDATE ----

    public function test_update_product(): void
    {
        $created = $this->createProduct('Old Name', 10.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'New Name', 'price' => 20.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        $this->assertTrue($data['success']);
        $this->assertSame($id, $data['ULID']);
        $this->assertSame('New Name', $data['name']);
        $this->assertEquals(20.0, $data['price']);
        $this->assertArrayHasKey('message', $data);
    }

    public function test_update_only_name(): void
    {
        $created = $this->createProduct('Original Name', 50.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Updated Name'])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertSame('Updated Name', $data['name']);
        $this->assertEquals(50.0, $data['price']);
    }

    public function test_update_only_price(): void
    {
        $created = $this->createProduct('Stable Name', 50.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['price' => 75.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertSame('Stable Name', $data['name']);
        $this->assertEquals(75.0, $data['price']);
    }

    public function test_update_product_with_empty_name_returns_400(): void
    {
        $created = $this->createProduct('Valid Name', 10.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => ''])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_update_product_with_negative_price_returns_400(): void
    {
        $created = $this->createProduct('Valid Name', 10.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['price' => -5.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_update_product_with_invalid_json_returns_400(): void
    {
        $created = $this->createProduct('Valid Name', 10.00);
        $id = $created['ULID'];

        $this->client->request(
            'PUT',
            "/api/products/{$id}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{invalid-json}'
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_update_product_returns_404_when_not_found(): void
    {
        $this->client->request(
            'PUT',
            '/api/products/non-existent-id',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Name', 'price' => 10.00])
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    // ---- DELETE ----

    public function test_delete_product(): void
    {
        $created = $this->createProduct('To Delete', 5.00);
        $id = $created['ULID'];

        $this->client->request('DELETE', "/api/products/{$id}");

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('message', $data);

        // Verify it's gone
        $this->client->request('GET', "/api/products/{$id}");
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function test_delete_product_returns_404_when_not_found(): void
    {
        $this->client->request('DELETE', '/api/products/non-existent-id');

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function test_deleted_product_no_longer_appears_in_list(): void
    {
        $this->createProduct('Keep Me', 10.00);
        $toDelete = $this->createProduct('Delete Me', 5.00);

        $this->client->request('DELETE', "/api/products/{$toDelete['ULID']}");
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/api/products');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame(1, $data['count']);
        $this->assertSame('Keep Me', $data['data'][0]['name']);
    }
}
