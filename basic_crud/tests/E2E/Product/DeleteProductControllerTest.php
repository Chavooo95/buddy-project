<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DeleteProductControllerTest extends WebTestCase
{
    public function test_it_deletes_a_product(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request(
            'POST',
            '/api/products',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Product', 'price' => 29.99])
        );

        $created = json_decode($client->getResponse()->getContent(), true);
        $ulid = $created['ulid'];

        $client->request('DELETE', '/api/products/' . $ulid);

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Product deleted successfully', $data['message']);
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/products/non-existent-id');

        $this->assertResponseStatusCodeSame(404);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Product not found', $data['message']);
    }
}
