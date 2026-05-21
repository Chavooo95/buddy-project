<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use App\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class UpdateProductControllerTest extends WebTestCase
{
    public function test_it_updates_a_product(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->withName('Old Name')->withPrice(10.00)->build();

        $repository->save($product);

        $client->request(
            'PUT',
            '/api/products/' . $product->id(),
            [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'New Name', 'price' => 99.99])
        );

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Product updated successfully', $data['message']);
        $this->assertEquals($product->id(), $data['ulid']);
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
