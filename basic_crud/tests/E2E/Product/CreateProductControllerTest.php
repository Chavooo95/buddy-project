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

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($product->name()->value, $body['data']['name']);
        $this->assertEquals($product->price()->value, $body['data']['price']);
        $this->assertNotEmpty($body['data']['ulid']);
    }

    public function test_it_points_the_location_header_at_the_new_product(): void
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

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            '/api/products/' . $body['data']['ulid'],
            $client->getResponse()->headers->get('Location')
        );
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

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('validation_failed', $body['error']['code']);
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

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('invalid_json', $body['error']['code']);
        $this->assertEquals('Invalid JSON provided', $body['error']['detail']);
    }
    public function test_if_creates_a_product_with_stringed_price(): void
    {
        $client = static::createClient();

        $product = (new ProductBuilder())->build();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $product->name()->value, 'price' => '10.98'])
        );

        $this->assertResponseStatusCodeSame(201);

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(10.98, $body['data']['price']);
    }

    public function test_it_returns_400_on_non_numeric_price(): void
    {
        $client = static::createClient();

        $product = (new ProductBuilder())->build();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $product->name()->value, 'price' => 'abc'])
        );

        $this->assertResponseStatusCodeSame(400);

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('validation_failed', $body['error']['code']);
        $this->assertEquals('price: must be numeric', $body['error']['detail']);
    }

    public function test_it_returns_400_on_unexpected_field(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Keyboard', 'price' => 29.99, 'discount' => 5])
        );

        $this->assertResponseStatusCodeSame(400);

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('validation_failed', $body['error']['code']);
        $this->assertEquals('discount: This field was not expected', $body['error']['detail']);
    }
}