<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Entity\Product;
use App\Product\Entity\ValueObjects\ProductId;
use App\Product\Repository\ProductRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

class ProductODMRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly DocumentManager $documentManager
    )
    {
    }

    public function findAll(): array
    {
        return array_values($this->documentManager->getRepository(Product::class)->findAll());
    }

    /**
     * Save product document
     * @throws MongoDBException
     */
    public function save(Product $entity): void
    {
        $this->documentManager->persist($entity);
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function remove(Product $entity): void
    {
        $this->documentManager->remove($entity);
        $this->documentManager->flush();
    }

    public function findByName(string $name): array
    {
        return array_values($this->documentManager->getRepository(Product::class)
            ->findBy(['name' => $name], ['name' => 'ASC']));
    }

    public function findByPartialName(string $name): array
    {
        return array_values($this->documentManager->getRepository(Product::class)
            ->findBy(['name' => new \MongoDB\BSON\Regex($name, 'i')], ['name' => 'ASC']));
    }

    public function find(ProductId $id): ?Product
    {
        return $this->documentManager->find(Product::class, $id->value);
    }
}