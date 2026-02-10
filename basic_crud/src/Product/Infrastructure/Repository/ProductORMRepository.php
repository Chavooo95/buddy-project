<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Entity\Product;
use App\Product\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductORMRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findAll(): array {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    /**
     * Save product entity
     */
    public function save(Product $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(Product $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function findByName(string $name): array
    {
        return $this->entityManager->createQueryBuilder('p')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();

        /*
         *return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
         */
    }

    public function find(string $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id);
    }
}
