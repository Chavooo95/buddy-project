# MongoDB Setup Instructions

## 1. Install Doctrine MongoDB ODM Bundle
composer require doctrine/mongodb-odm-bundle

## 2. Enable the bundle in config/bundles.php
# Add this line:
# Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle::class => ['all' => true],

## 3. Install MongoDB PHP Driver
# On Ubuntu/Debian:
sudo apt-get install php-mongodb

# On CentOS/RHEL:
sudo yum install php-mongodb

# Or using pecl:
sudo pecl install mongodb