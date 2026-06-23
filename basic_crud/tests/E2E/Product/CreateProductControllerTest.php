<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class CreateProductControllerTest extends WebTestCase
{
    public function test_it_creates_a_product(): void
    {
        $client = static::createClient();
        $product = (new ProductBuilder())->build();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $product->name()->value, 'price' => $product->price()->value])
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Product created successfully', $data['message']);
        $this->assertEquals($product->name()->value, $data['name']);
        $this->assertEquals($product->price()->value, $data['price']);
        $this->assertNotEmpty($data['ulid']);
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

    public function test_it_returns_400_on_invalid_JSON(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'missing JSON'
        );

        $this->assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Invalid JSON provided', $data['message']);
    }
}
