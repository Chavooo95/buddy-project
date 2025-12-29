<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ProductServiceFactory
{
    public function __construct(
        private ProductORMService $ormService,
        private ProductODMService $odmService,
        #[Autowire('%env(DATABASE_TYPE)%')]
        private string $databaseType = 'mysql'
    ) {
    }

    public function getProductService(?string $type = null): ProductServiceInterface
    {
        $selectedType = $type ?? $this->databaseType;
        
        return match ($selectedType) {
            'mongodb' => $this->odmService,
            'mysql', 'postgresql' => $this->ormService,
            default => $this->ormService
        };
    }

    public function getDatabaseType(): string
    {
        return $this->databaseType;
    }
}