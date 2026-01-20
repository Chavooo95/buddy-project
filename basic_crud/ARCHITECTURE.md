# Product CRUD API - Unified Architecture

This project implements a unified architecture for a Product CRUD API using Symfony, with support for both ORM and ODM through XML mapping definitions.

## Architecture Overview

### 📁 Project Structure

```
src/
├── Controller/
│   └── ProductController.php      # HTTP request handling
├── Service/
│   └── ProductService.php         # Business logic layer
├── Repository/
│   └── ProductRepository.php      # Data access layer
├── Entity/
│   └── Product.php                # Domain entity (no annotations)
├── Interface/
│   └── ProductRepositoryInterface.php  # Repository contract
├── DTO/
│   └── ProductRequestDTO.php      # Data transfer object
└── Infrastructure/
    └── Repository/
        └── Doctrine/
            └── Mapping/
                ├── Product.orm.xml    # ORM mapping definition
                └── Product.odm.xml    # ODM mapping definition
```

### 🏗️ Layer Responsibilities

1. **Controller Layer** (`ProductController`)
   - Handles HTTP requests/responses
   - Input validation and JSON parsing
   - Error handling and response formatting
   - Delegates business logic to Service layer

2. **Service Layer** (`ProductService`)
   - Contains business logic and validation
   - Orchestrates operations between layers
   - Handles complex business rules
   - Manages transactions

3. **Repository Layer** (`ProductRepository`)
   - Data access abstraction
   - Implements repository interface
   - Custom query methods
   - Database operations

4. **Entity Layer** (`Product`)
   - Domain model representation
   - No framework-specific annotations
   - Pure PHP class with business methods
   - Configured via XML mapping

### 🗂️ XML Mapping Configuration

#### ORM Configuration (`Product.orm.xml`)
```xml
<entity name="App\Entity\Product" table="products" repository-class="App\Repository\ProductRepository">
    <id name="id" type="integer">
        <generator strategy="IDENTITY" />
    </id>
    <field name="name" type="string" length="255" nullable="false" />
    <field name="price" type="float" nullable="false" />
    <field name="createdAt" type="datetime" nullable="false" />
    <field name="updatedAt" type="datetime" nullable="true" />
</entity>
```

#### ODM Configuration (`Product.odm.xml`)
```xml
<document name="App\Entity\Product" collection="products">
    <id field-name="id" type="id" />
    <field field-name="name" type="string" />
    <field field-name="price" type="float" />
    <field field-name="createdAt" type="date" />
    <field field-name="updatedAt" type="date" />
</document>
```

## 🚀 API Endpoints

### Base URL: `/api/products`

| Method | Endpoint | Description | Request Body |
|--------|----------|-------------|--------------|
| GET    | `/`      | List all products (with optional search) | - |
| GET    | `/{id}`  | Get product by ID | - |
| POST   | `/`      | Create new product | `{"name": "string", "price": float}` |
| PUT    | `/{id}`  | Update product | `{"name": "string", "price": float}` |
| DELETE | `/{id}`  | Delete product | - |

### Query Parameters

- `search` (string): Search products by name (partial match)

### Response Format

All responses follow this structure:
```json
{
  "success": true|false,
  "message": "descriptive message",
  "data": {...},
  "error": "error details (only on failure)"
}
```

## 🔧 Features

### Entity Features
- Automatic timestamp management (createdAt/updatedAt)
- Type-safe getters/setters
- Array serialization for API responses
- Framework-agnostic design

### Repository Features
- Interface-based design for better testability
- Custom query methods:
  - `findByName()`: Search by partial name match
  - `findByPriceRange()`: Filter by price range
  - `findCreatedAfter()`: Filter by creation date

### Service Layer Features
- Comprehensive input validation
- Business logic encapsulation
- Error handling with descriptive messages
- Transaction management

### Controller Features
- RESTful API design
- JSON input/output handling
- Comprehensive error responses
- Search functionality

## 🛠️ Development Commands

```bash
# Setup project
make setup

# Start development environment
make dev

# Database operations
make db-create
make db-schema-update

# Clear cache
make cache-clear

# Run tests
make test
```

## 📋 Configuration

### Doctrine Configuration
The project uses XML mapping configuration located in `config/packages/doctrine.yaml`:

```yaml
doctrine:
  orm:
    auto_mapping: false
    mappings:
      App:
        type: xml
        dir: '%kernel.project_dir%/src/Infrastructure/Repository/Doctrine/Mapping'
        prefix: 'App\Entity'
```

### Service Configuration
Interface binding is configured in `config/services.yaml`:

```yaml
App\Interface\ProductRepositoryInterface:
  alias: App\Repository\ProductRepository
```

## 🧪 Testing

The architecture supports easy testing through:
- Dependency injection with interfaces
- Separated business logic in services
- Mockable repository layer
- DTOs for input validation

## 🎯 Benefits of This Architecture

1. **Separation of Concerns**: Each layer has a specific responsibility
2. **Testability**: Interface-based design allows easy mocking
3. **Flexibility**: XML mapping supports both ORM and ODM
4. **Maintainability**: Clean code structure with clear dependencies
5. **Extensibility**: Easy to add new features without breaking existing code
6. **Framework Independence**: Entity layer is not tied to specific framework annotations

## 🔄 Migration from Annotation-based

This structure migrates from:
- ❌ Annotation-based entities with tight coupling
- ❌ Direct repository injection in controllers
- ❌ Mixed concerns in single files

To:
- ✅ XML-based mapping with loose coupling
- ✅ Service layer with business logic
- ✅ Interface-based repository pattern
- ✅ Clean separation of concerns