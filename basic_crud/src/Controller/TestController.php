<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/test')]
class TestController extends AbstractController
{
    #[Route('/database-type', methods: ['GET'])]
    public function getDatabaseType(Request $request): JsonResponse
    {
        $databaseType = $request->query->get('db', 'mysql');
        
        return $this->json([
            'message' => 'Database type selection test',
            'selected_database' => $databaseType,
            'available_databases' => ['mysql', 'postgresql', 'mongodb'],
            'status' => 'Service layer is working',
            'endpoints_info' => [
                'Test MySQL' => 'GET /api/products?db=mysql',
                'Test MongoDB' => 'GET /api/products?db=mongodb',
                'Test PostgreSQL' => 'GET /api/products?db=postgresql'
            ]
        ]);
    }

    #[Route('/services', methods: ['GET'])]
    public function testServices(): JsonResponse
    {
        return $this->json([
            'message' => 'Service registration test',
            'services' => [
                'ProductORMService' => 'Available for MySQL/PostgreSQL',
                'ProductODMService' => 'Available for MongoDB',
                'ProductServiceFactory' => 'Router service for database selection'
            ],
            'implementation_status' => 'All service classes have been created',
            'next_step' => 'Test actual database connections'
        ]);
    }
}