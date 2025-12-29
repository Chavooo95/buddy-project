<?php
declare(strict_types=1);

namespace App\Repository\Document;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ProductRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        parent::__construct($dm, $dm->getUnitOfWork(), $dm->getClassMetadata(Product::class));
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findById($id): ?Product
    {
        /** @var Product|null */
        return parent::find($id);
    }

    public function save(Product $product): void
    {
        $this->getDocumentManager()->persist($product);
        $this->getDocumentManager()->flush();
    }

    public function remove(Product $product): void
    {
        $this->getDocumentManager()->remove($product);
        $this->getDocumentManager()->flush();
    }
}