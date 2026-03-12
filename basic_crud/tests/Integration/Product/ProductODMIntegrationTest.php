<?php

declare(strict_types=1);

namespace Test\Integration\Product;

use App\Product\Entity\Product;

class ProductODMIntegrationTest extends ProductIntegrationTestCase
{
    protected function setUp(): void
    {
        $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb://mongodb:27017';
        $parsed = parse_url($mongoUri);
        $host = $parsed['host'] ?? 'mongodb';
        $port = $parsed['port'] ?? 27017;

        $connection = @fsockopen($host, (int) $port, $errno, $errstr, 1);
        if ($connection === false) {
            $this->markTestSkipped("MongoDB is not reachable at {$host}:{$port} — skipping ODM integration tests.");
        }
        fclose($connection);

        parent::setUp();
    }

    protected function clearDatabase(): void
    {
        $dm = static::getContainer()->get('doctrine_mongodb.odm.document_manager');
        $dm->getDocumentCollection(Product::class)->deleteMany([]);
    }
}
