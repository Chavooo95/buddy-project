<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Interface\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Product Repository
 * Configured through XML mapping for both ORM and ODM
 *
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Save product entity
     */
    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove product entity
     */
    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find products by name (partial match)
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products by price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price >= :minPrice')
            ->andWhere('p.price <= :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products created after a specific date
     */
    public function findCreatedAfter(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.createdAt >= :date')
            ->setParameter('date', $date)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}