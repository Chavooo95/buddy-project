<?php

declare(strict_types=1);

namespace Test\Integration\Product;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductORMIntegrationTest extends ProductIntegrationTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        $options['environment'] = 'test_orm';
        return parent::createKernel($options);
    }

    private static function checkPostgresReachable(): void
    {
        $url = $_ENV['DATABASE_URL'] ?? 'postgresql://app_user:app_password@database:5432/app_db';
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? 'database';
        $port = $parsed['port'] ?? 5432;

        $connection = @fsockopen($host, (int) $port, $errno, $errstr, 1);
        if ($connection === false) {
            self::markTestSkipped("PostgreSQL is not reachable at {$host}:{$port} — skipping ORM integration tests.");
        }
        fclose($connection);
    }

    public static function setUpBeforeClass(): void
    {
        self::checkPostgresReachable();
        parent::setUpBeforeClass();

        static::bootKernel(['environment' => 'test_orm']);
        $em = static::getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $schemaTool->updateSchema($em->getMetadataFactory()->getAllMetadata(), true);
        static::ensureKernelShutdown();
    }

    protected function setUp(): void
    {
        self::checkPostgresReachable();
        parent::setUp();
    }

    protected function clearDatabase(): void
    {
        $connection = static::getContainer()->get('doctrine')->getConnection();
        $connection->executeStatement('TRUNCATE TABLE products RESTART IDENTITY CASCADE');
    }
}
