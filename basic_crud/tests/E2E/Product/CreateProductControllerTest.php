<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateProductControllerTest extends WebTestCase
{
    public function test_it_creates_a_product(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Product', 'price' => 29.99])
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Product created successfully', $data['message']);
        $this->assertEquals('Test Product', $data['name']);
        $this->assertEquals(29.99, $data['price']);
        $this->assertNotEmpty($data['ULID']);
    }

    public function test_it_returns_400_on_missing_name(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['price' => 29.99])
        );

        $this->assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Validation error', $data['message']);
    }
}
