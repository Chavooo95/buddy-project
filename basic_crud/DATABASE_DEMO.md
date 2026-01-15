# Database Switch Demo

## 🎯 **The Power of Unified Architecture**

This demonstrates how the **same code** works seamlessly with different databases:

### Same Entity Class
```php
// src/Entity/Product.php - NO CHANGES NEEDED
class Product
{
    private mixed $id = null;
    private ?string $name = null;
    private ?float $price = null;
    // ... same for all databases
}
```

### Same API Endpoints
```bash
# These work identically regardless of database:
POST   /api/products              # Create product
GET    /api/products              # List products  
GET    /api/products/{id}         # Get product
PUT    /api/products/{id}         # Update product
DELETE /api/products/{id}         # Delete product
```

### Same Service Logic
```php
// src/Service/ProductService.php - NO CHANGES NEEDED
public function createProduct(array $data): Product
{
    $this->validateProductData($data);
    $product = new Product();
    $product->setName($data['name']);
    $product->setPrice((float) $data['price']);
    $this->saveProduct($product);
    return $product;
}
```

### Same JSON Responses
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "price": 29.99,
    "createdAt": "2026-01-15 15:47:03",
    "updatedAt": null
  }
}
```

## 🔄 **What Changes When Switching Databases**

| Component | SQLite | MySQL | MongoDB |
|-----------|--------|--------|---------|
| **Entity** | ✅ Same | ✅ Same | ✅ Same |
| **Service** | ✅ Same | ✅ Same | ✅ Same |
| **Controller** | ✅ Same | ✅ Same | ✅ Same |
| **API Endpoints** | ✅ Same | ✅ Same | ✅ Same |
| **JSON Responses** | ✅ Same | ✅ Same | ✅ Same |
| **Configuration** | Different | Different | Different |
| **Repository Queries** | ✅ Same | ✅ Same | ⚠️ Slight changes |

## 🚀 **Quick Switch Test**

### 1. Currently Using SQLite:
```bash
./switch_database.sh sqlite
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name": "SQLite Product", "price": 19.99}'
```

### 2. Switch to MySQL:
```bash
./switch_database.sh mysql
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name": "MySQL Product", "price": 29.99}'
```

### 3. Switch to MongoDB:
```bash
./switch_database.sh mongodb  
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name": "MongoDB Product", "price": 39.99}'
```

**Result**: Same API, same responses, different storage! 🎉

## 🎨 **Architecture Benefits**

1. **Database Agnostic**: Your business logic doesn't care about the storage
2. **Easy Migration**: Switch databases without breaking client code
3. **Technology Flexibility**: Choose the best database for each environment
4. **Future Proof**: Add new databases without major refactoring
5. **Testing Flexibility**: Use SQLite for tests, PostgreSQL for production

This is the power of clean architecture! 🏗️