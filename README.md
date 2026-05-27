# Buddy Project

Symfony 6.4 CRUD API for `Product` with a single entity mapped against **two interchangeable persistence layers**: PostgreSQL (via Doctrine ORM) and MongoDB (via Doctrine ODM). Switching storage is a one-line change in `config/services.yaml`.

The Symfony app lives in [`basic_crud/`](./basic_crud). All commands below assume that as the working directory.

## Requirements

- **Docker** with the `compose` plugin (Docker Desktop, OrbStack, or Colima).
- *(Optional)* PHP 8.3+ and Composer on host for IDE introspection. Not required to run the project — everything (PHP 8.3, Postgres 15, MongoDB 7, composer) runs inside containers.

## Quick start

```bash
cd basic_crud

# 1. Build, install dependencies (in container) and start everything
make dev

# 2. Create the schema
docker compose exec php php bin/console doctrine:database:create --if-not-exists
docker compose exec php php bin/console doctrine:schema:update --force --complete

# 3. (Optional, only if you plan to use MongoDB) create collections + indexes
docker compose exec php php bin/console doctrine:mongodb:schema:create
```

The API is now at <http://localhost:8000>. `make dev` is idempotent — it copies `.env` from `.env.dev` if missing, runs `composer install` inside the project's php image, and brings up postgres / mongo / php.

If you prefer to run composer on the host (PHP 8.3+, with `ext-mongodb` installed via `pecl install mongodb`), use `make install-host` instead of `make install`.

## Endpoints

All under `/api/products`. Bodies are JSON.

| Method | Path | Body | Description |
|---|---|---|---|
| POST   | `/api/products`              | `{"name": "...", "price": 9.99}` | Create |
| GET    | `/api/products`              | —                                | List all |
| GET    | `/api/products/{id}`         | —                                | Get by ULID |
| GET    | `/api/products/name/{name}`  | —                                | Find by name |
| PUT    | `/api/products/{id}`         | `{"name": "...", "price": 12.5}` | Update |
| DELETE | `/api/products/{id}`         | —                                | Delete |

`id` is a ULID assigned at creation. The full route table is also available with `docker compose exec php php bin/console debug:router`.

## Switching the storage backend

The repository implementation used at runtime is wired by an alias in [`config/services.yaml`](./basic_crud/config/services.yaml):

```yaml
App\Product\Repository\ProductRepositoryInterface:
  alias: App\Product\Infrastructure\Repository\ProductORMRepository   # PostgreSQL
# alias: App\Product\Infrastructure\Repository\ProductODMRepository   # MongoDB
```

Change the alias, then `docker compose restart php`. Controllers, use cases, the `Product` entity, and JSON responses are unchanged — that's the point of the abstraction.

## Running tests

```bash
# All tests
docker compose exec php ./vendor/bin/phpunit

# Filter by name pattern
docker compose exec php ./vendor/bin/phpunit --filter ProductCreator
```

The test suite is organised by type:
- `tests/Product/UseCase/` — unit tests using an `InMemoryProductRepository` (no DB).
- `tests/Integration/Product/Repository/` — hit a real Postgres / Mongo.
- `tests/E2E/Product/` — HTTP-level against the running app.

`make test` is equivalent to the phpunit command above.

## Project layout

```
basic_crud/
├── src/Product/
│   ├── Controller/                 # HTTP entry points (one per endpoint)
│   ├── Entity/                     # Product
│   ├── UseCase/                    # ProductCreator, ProductLister, ...
│   ├── Repository/                 # ProductRepositoryInterface
│   └── Infrastructure/Repository/
│       ├── ProductORMRepository.php  # PostgreSQL
│       └── ProductODMRepository.php  # MongoDB
├── tests/
│   ├── Data/                       # In-memory repo + builders
│   ├── Product/UseCase/            # Unit
│   ├── Integration/                # Repo + real DB
│   └── E2E/                        # HTTP
├── config/                         # Symfony config (services.yaml decides ORM vs ODM)
├── docker/                         # Dockerfile for the PHP image (installs ext-mongodb)
├── docker-compose.yml              # postgres + mongo + php server
└── Makefile
```

## Useful Make targets

| Target | What it does |
|---|---|
| `make dev`            | Build image + install deps (in container) + start postgres / mongo / php |
| `make install`        | Install dependencies inside the project's php image (default) |
| `make install-host`   | Install dependencies using host composer (needs PHP 8.3+ and `ext-mongodb`) |
| `make up` / `make down` / `make restart` | Container lifecycle |
| `make logs`           | Tail container logs |
| `make test`           | Run phpunit inside the PHP container |
| `make db-schema-update` | `doctrine:schema:update --force --complete` inside container |
| `make test-db-setup`  | Create + migrate the test database |
| `make cache-clear`    | Clear Symfony cache (host PHP, env=dev) |
| `make status`         | Quick diagnostics |

Run `make help` for the full list.

## Known gotchas

- **`make cache-clear` and `make db-create` use host PHP.** They will fail if your host doesn't have PHP 8.3+ and `ext-mongodb`. For DB ops the in-container equivalents are reliable: `docker compose exec php php bin/console doctrine:...`.
- **MongoDB needs `doctrine:mongodb:schema:create` once** to create the indexes; collections are created lazily on first insert.
- **`DATABASE_DEMO.md` is out of date.** It mentions SQLite/MySQL and a `switch_database.sh` that doesn't exist; the real switch is the alias in `services.yaml`.

## Stack

PHP 8.3 · Symfony 6.4 · Doctrine ORM 2 · Doctrine MongoDB ODM 5 · PostgreSQL 15 · MongoDB 7 · PHPUnit 12 · Docker Compose.
