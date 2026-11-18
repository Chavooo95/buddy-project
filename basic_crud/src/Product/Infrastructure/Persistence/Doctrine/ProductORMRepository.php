<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine;

use App\Product\Domain\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
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
        return $this->entityManager->getRepository(Product::class)->findBy(['name' => $name]);
    }

    public function findByPartialName(string $name): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->contains('name', $name))
            ->orderBy(['name' => Order::Ascending]);

        return array_values($this->entityManager->getRepository(Product::class)->matching($criteria)->toArray());
    }

    public function find(ProductId $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id->value);
    }
}
