<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use App\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class FindProductsByNameControllerTest extends WebTestCase
{
    public function test_it_finds_products_by_exact_name(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->withName('Keyboard')->build();

        $repository->save($product);

        $client->request('GET', '/api/products/name/Keyboard');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertGreaterThanOrEqual(1, $data['count']);
        $this->assertEquals('Keyboard', $data['data'][0]['name']);
    }

    public function test_it_does_not_return_partial_matches(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->withName('Keyboard')->withPrice(49.99)->build();

        $repository->save($product);

        $client->request('GET', '/api/products/name/Key');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals(0, $data['count']);
        $this->assertEmpty($data['data']);
    }

    public function test_it_returns_empty_when_no_product_found(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/name/non-existent-product');

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals(0, $data['count']);
        $this->assertEmpty($data['data']);
    }
}
