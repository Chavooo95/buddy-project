# Database Switching Guide

This guide shows you how to switch between SQLite, MySQL, and MongoDB using our unified architecture.

## 🔄 **Current Setup: SQLite**
✅ Currently working with SQLite database for development.

---

## 🐬 **Switching to MySQL**

### Step 1: Update Environment Configuration
```bash
# Edit .env.dev file
cp config_templates/.env.mysql temp_mysql_config
# Then copy the DATABASE_URL and credentials to .env.dev
```

### Step 2: Create MySQL Database
```bash
# Make sure MySQL server is running
sudo systemctl start mysql  # On Linux
brew services start mysql   # On macOS

# Create database and user
mysql -u root -p
CREATE DATABASE app_db;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'app_password';
GRANT ALL PRIVILEGES ON app_db.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Update Schema
```bash
# Clear cache
php bin/console cache:clear

# Create database schema
php bin/console doctrine:schema:create

# Or update if database exists
php bin/console doctrine:schema:update --force
```

### Step 4: Test API
```bash
# Start server
php -S localhost:8000 -t public

# Test endpoints
curl -X GET http://localhost:8000/api/products
```

---

## 🍃 **Switching to MongoDB**

### Step 1: Install Dependencies
```bash
# Install MongoDB ODM Bundle
composer require doctrine/mongodb-odm-bundle

# Install PHP MongoDB extension
sudo apt-get install php-mongodb  # Ubuntu/Debian
# or
sudo pecl install mongodb
```

### Step 2: Enable MongoDB Bundle
Add to `config/bundles.php`:
```php
Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle::class => ['all' => true],
```

### Step 3: Configure MongoDB
```bash
# Copy MongoDB configuration
cp config_templates/doctrine_mongodb.yaml config/packages/

# Update .env.dev with MongoDB settings
cp config_templates/.env.mongodb temp_mongodb_config
# Then copy the MONGODB_* variables to .env.dev
```

### Step 4: Update Repository for MongoDB
The repository needs slight modifications for MongoDB:

```php
// src/Repository/ProductRepository.php
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ProductRepository extends DocumentRepository implements ProductRepositoryInterface
{
    // MongoDB-specific query methods
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder()
            ->field('name')->equals(new \MongoDB\BSON\Regex($name, 'i'))
            ->getQuery()
            ->execute()
            ->toArray();
    }
}
```

### Step 5: Create MongoDB Collections
```bash
# MongoDB doesn't need explicit schema creation
# Collections are created automatically on first insert

# Clear cache
php bin/console cache:clear

# Test the API
php -S localhost:8000 -t public
```

---

## 🔧 **Key Differences by Database**

| Feature | SQLite | MySQL | MongoDB |
|---------|--------|--------|---------|
| **Setup Complexity** | ⭐ Simple | ⭐⭐ Medium | ⭐⭐⭐ Complex |
| **Schema Creation** | `doctrine:schema:create` | `doctrine:schema:create` | Automatic |
| **ID Strategy** | AUTO | AUTO/IDENTITY | ObjectId |
| **Queries** | SQL via QueryBuilder | SQL via QueryBuilder | MongoDB Query Language |
| **Transactions** | Limited | Full Support | Limited |

---

## 📂 **File Changes Required**

### For MySQL:
- ✅ **No code changes needed** - XML mapping works as-is
- ✅ Update `.env.dev` with MySQL connection
- ✅ Run schema creation commands

### For MongoDB:
- ⚠️ **Minor Repository changes** - Query syntax differs
- ⚠️ **Add MongoDB bundle configuration**
- ⚠️ Update `.env.dev` with MongoDB connection
- ⚠️ Install additional dependencies

---

## 🎯 **Benefits of Our Unified Architecture**

1. **Same Entity Class** - Works with all databases
2. **Same Service Layer** - Business logic unchanged
3. **Same Controller** - API endpoints identical
4. **Same XML Mappings** - Minimal configuration changes
5. **Same API Response Format** - Client code unchanged

---

## 🚀 **Quick Switch Commands**

### SQLite (Current):
```bash
# Already working!
```

### MySQL:
```bash
# Update DATABASE_URL in .env.dev to MySQL
# Run: php bin/console doctrine:schema:create
```

### MongoDB:
```bash
# composer require doctrine/mongodb-odm-bundle
# Update config files and .env.dev
# Minor repository modifications
```

The beauty is that **your API clients won't notice any difference** - the same endpoints work the same way regardless of the underlying database! 🎉