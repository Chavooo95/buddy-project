<?php

declare(strict_types=1);

namespace Test\E2E\Product;

use App\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ListProductsControllerTest extends WebTestCase
{
    public function test_it_lists_products(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->build();

        $repository->save($product);

        $client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(200);

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertGreaterThanOrEqual(1, $body['count']);
        $this->assertNotEmpty($body['data']);
    }

    public function test_it_filters_products_by_search(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $repository = $container->get(ProductRepositoryInterface::class);
        $product = (new ProductBuilder())->withName('Keyboard')->withPrice(49.99)->build();

        $repository->save($product);

        $client->request('GET', '/api/products?search=Key');

        $this->assertResponseStatusCodeSame(200);

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertGreaterThanOrEqual(1, $body['count']);
        $this->assertEquals('Keyboard', $body['data'][0]['name']);
    }
}
