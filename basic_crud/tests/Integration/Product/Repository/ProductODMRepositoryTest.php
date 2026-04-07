<?php

declare(strict_types=1);

namespace Test\Integration\Product\Repository;

use App\Product\Entity\Product;
use App\Product\Infrastructure\Repository\ProductODMRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\Data\Product\Domain\ProductBuilder;

final class ProductODMRepositoryTest extends KernelTestCase
{
    private ProductODMRepository $subject;
    private DocumentManager $documentManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->documentManager = $container->get(DocumentManager::class);
        $this->subject = new ProductODMRepository($this->documentManager);

        $this->documentManager
            ->getDocumentCollection(Product::class)
            ->deleteMany([]);
    }

    public function test_it_saves_a_product(): void
    {
        $product = (new ProductBuilder())->build();

        $this->subject->save($product);

        $found = $this->subject->find($product->id());

        $this->assertNotNull($found);
        $this->assertSame($product->id(), $found->id());
        $this->assertSame($product->name(), $found->name());
        $this->assertSame($product->price(), $found->price());

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
}