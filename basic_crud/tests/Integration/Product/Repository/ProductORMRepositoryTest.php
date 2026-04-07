<?php

declare(strict_types=1);

namespace Test\Integration\Product\Repository;

use App\Product\Infrastructure\Repository\ProductORMRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductORMRepositoryTest extends KernelTestCase
{
    private ProductORMRepository $subject;
    private Connection $db;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->subject = new ProductORMRepository(
            $container->get(EntityManagerInterface::class)
        );
        $this->db = $container->get('doctrine')->getConnection();
        $this->db->executeStatement('TRUNCATE TABLE products RESTART IDENTITY CASCADE');
    }

    public function test_it_saves_a_product(): void
    {
        $product = (new ProductBuilder())->build();

        $this->subject->save($product);

        $row = $this->db->fetchAssociative(
            'SELECT * FROM products WHERE id = ?',
            [$product->id()]
        );

        $this->assertNotFalse($row);
        $this->assertSame($product->id(), $row['id']);
        $this->assertSame($product->name(), $row['name']);
        $this->assertSame($product->price(), (float) $row['price']);
    }

    public function test_it_finds_a_product_by_id(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->withPrice(49.99)->build();
        $this->subject->save($product);

        $found = $this->subject->find($product->id());

        $this->assertNotNull($found);
        $this->assertSame($product->id(), $found->id());
        $this->assertSame($product->name(), $found->name());
        $this->assertSame($product->price(), $found->price());
    }

    public function test_it_returns_null_when_product_not_found(): void
    {
        $found = $this->subject->find('non-existent-id');

        $this->assertNull($found);
    }

    public function test_it_returns_all_products(): void
    {
        $products = [
            (new ProductBuilder())->build(),
            (new ProductBuilder())->build(),
        ];
        foreach ($products as $product) {
            $this->subject->save($product);
        }
        $found = $this->subject->findAll();
        $this->assertCount(2, $found);
    }

    public function test_it_deletes_a_product(): void
    {
        $product = (new ProductBuilder())->build();
        $this->subject->save($product);
        $this->subject->remove($product);
        $found = $this->subject->find($product->id());
        $this->assertNull($found);
    }

    public function test_it_finds_a_product_by_partial_name(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->build();
        $this->subject->save($product);
        $found = $this->subject->findByPartialName('Key');
        $this->assertCount(1, $found);
        $this->assertSame($product->id(), $found[0]->id());
    }

    public function test_it_finds_a_product_by_exact_name(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->build();
        $this->subject->save($product);
        $found = $this->subject->findByName('Keyboard');
        $this->assertCount(1, $found);
        $this->assertSame($product->id(), $found[0]->id());
    }

    public function test_find_by_name_does_not_match_partial_name(): void
    {
        $product = (new ProductBuilder())->withName('Keyboard')->build();
        $this->subject->save($product);
        $found = $this->subject->findByName('Key');
        $this->assertCount(0, $found);
    }
}
