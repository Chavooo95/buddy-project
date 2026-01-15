# API Testing Examples

## Test the unified Product CRUD API

### 1. Get all products
```bash
curl -X GET http://localhost:8000/api/products
```

### 2. Create a new product
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"name": "Test Product", "price": 29.99}'
```

### 3. Get product by ID
```bash
curl -X GET http://localhost:8000/api/products/1
```

### 4. Update a product
```bash
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Product", "price": 39.99}'
```

### 5. Search products by name
```bash
curl -X GET "http://localhost:8000/api/products?search=test"
```

### 6. Delete a product
```bash
curl -X DELETE http://localhost:8000/api/products/1
```

## Expected Response Format

All responses will have this structure:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "price": 29.99,
    "createdAt": "2026-01-15 15:39:00",
    "updatedAt": null
  },
  "message": "Operation completed successfully"
}
```

Or for errors:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message"
}
```

## Note
Since the database is not configured yet, you might see database connection errors. 
The structure is ready and will work once a database connection is established.