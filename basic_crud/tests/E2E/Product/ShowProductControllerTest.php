<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use App\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Ulid;
use Test\Data\Product\Domain\ProductBuilder;

final class ShowProductControllerTest extends WebTestCase
{
    public function test_it_shows_a_product(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->build();

        $repository->save($product);

        $client->request('GET', '/api/products/' . $product->id()->value);

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals($product->id()->value, $data['ulid']);
        $this->assertEquals($product->name()->value, $data['name']);
        $this->assertEquals($product->price()->value, $data['price']);
    }

    public function test_it_keeps_the_zero_fraction_of_a_whole_price(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->withPrice(10.00)->build();

        $repository->save($product);

        $client->request('GET', '/api/products/' . $product->id()->value);

        $this->assertResponseStatusCodeSame(200);
        $this->assertStringContainsString('"price":10.0', $client->getResponse()->getContent());
    }

    public function test_it_returns_404_when_product_not_found(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/' . new Ulid());

        $this->assertResponseStatusCodeSame(404);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Product not found', $data['message']);
    }

    public function test_it_returns_400_on_malformed_id(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/non-existent-id');

        $this->assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Validation error', $data['message']);
        $this->assertEquals('Product id "non-existent-id" is not a valid ULID', $data['error']);
    }
}
