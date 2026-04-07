<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ListProductsControllerTest extends WebTestCase
{
    public function test_it_lists_products(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $client->request(
            'POST',
            '/api/products',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Product', 'price' => 29.99])
        );

        $client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertGreaterThanOrEqual(1, $data['count']);
        $this->assertNotEmpty($data['data']);
    }

    public function test_it_filters_products_by_search(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $client->request(
            'POST',
            '/api/products',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Keyboard', 'price' => 49.99])
        );

        $client->request('GET', '/api/products?search=Key');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertGreaterThanOrEqual(1, $data['count']);
        $this->assertEquals('Keyboard', $data['data'][0]['name']);
    }
}
