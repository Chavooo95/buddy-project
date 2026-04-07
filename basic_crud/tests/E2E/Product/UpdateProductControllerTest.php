<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UpdateProductControllerTest extends WebTestCase
{
    public function test_it_updates_a_product(): void
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request(
            'POST',
            '/api/products',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Old Name', 'price' => 10.00])
        );

        $created = json_decode($client->getResponse()->getContent(), true);
        $ulid = $created['ulid'];

        $client->request(
            'PUT',
            '/api/products/' . $ulid,
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'New Name', 'price' => 99.99])
        );

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Product updated successfully', $data['message']);
        $this->assertEquals($ulid, $data['ulid']);
        $this->assertEquals('New Name', $data['name']);
        $this->assertEquals(99.99, $data['price']);
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/api/products/non-existent-id',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'New Name', 'price' => 99.99])
        );

        $this->assertResponseStatusCodeSame(404);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Product not found', $data['message']);
    }
}
