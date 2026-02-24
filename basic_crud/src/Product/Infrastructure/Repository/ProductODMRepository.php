<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Entity\Product;
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
        return $this->documentManager->getRepository(Product::class)->findAll();
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

    /**
     * @throws MongoDBException
     */
    public function findByName(string $name): array
    {
        return $this->documentManager->createQueryBuilder(Product::class)
            ->field('name')->equals(new \MongoDB\BSON\Regex($name, 'i'))
            ->sort('name', 'ASC')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function find(string $id): ?Product
    {
        return $this->documentManager->find(Product::class, $id);
    }
}