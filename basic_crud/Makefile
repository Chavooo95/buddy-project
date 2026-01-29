# Basic CRUD Symfony Project Makefile

# Variables
PHP = php
COMPOSER = composer

DOCKER_COMPOSE = $(shell \
	if command -v docker-compose >/dev/null 2>&1; then \
		echo docker-compose; \
	elif command -v docker >/dev/null 2>&1 && docker compose version >/dev/null 2>&1; then \
		echo "docker compose"; \
	fi \
)

CONSOLE = $(PHP) bin/console

# Default environment
ENV ?= dev

# Colors for pretty output
RED = \033[0;31m
GREEN = \033[0;32m
YELLOW = \033[1;33m
BLUE = \033[0;34m
NC = \033[0m # No Color

.PHONY: help install setup up down build restart logs clean test cache-clear db-create db-migrate db-fixtures serve stop

# Default target
help: ## Show this help message
	@echo "$(GREEN)Basic CRUD Symfony Project$(NC)"
	@echo "$(YELLOW)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(BLUE)%-20s$(NC) %s\n", $$1, $$2}'

# Installation and Setup
install: ## Install PHP dependencies with Composer
	@echo "$(YELLOW)Installing dependencies...$(NC)"
	$(COMPOSER) install
	@echo "$(GREEN)Dependencies installed successfully!$(NC)"

setup: install ## Full project setup (install dependencies + environment setup)
	@echo "$(YELLOW)Setting up project environment...$(NC)"
	@if [ ! -f .env ]; then \
		cp .env.dev .env && \
		echo "$(GREEN)Environment file created from .env.dev$(NC)"; \
	fi
	@echo "$(GREEN)Project setup completed!$(NC)"

# Docker commands
build: ## Build Docker containers
	@echo "$(YELLOW)Building Docker containers...$(NC)"
	$(DOCKER_COMPOSE) build
	@echo "$(GREEN)Docker containers built!$(NC)"

up: ## Start Docker containers
	@echo "$(YELLOW)Starting Docker containers...$(NC)"
	$(DOCKER_COMPOSE) up -d
	@echo "$(GREEN)Docker containers started!$(NC)"

down: ## Stop Docker containers
	@echo "$(YELLOW)Stopping Docker containers...$(NC)"
	$(DOCKER_COMPOSE) down
	@echo "$(GREEN)Docker containers stopped!$(NC)"

restart: down up ## Restart Docker containers

logs: ## Show Docker container logs
	$(DOCKER_COMPOSE) logs -f

# Development commands
serve: ## Start PHP development server (host) on localhost:8000
	@echo "$(YELLOW)Starting PHP development server on localhost:8000...$(NC)"
	$(PHP) -S localhost:8000 -t public

serve-local: ## Start PHP development server (host) on localhost:8001
	@echo "$(YELLOW)Starting PHP development server on localhost:8001...$(NC)"
	$(PHP) -S localhost:8001 -t public

dev: setup up ## Start development environment using Docker (no host server)
	@echo "$(GREEN)Docker environment is up. Open http://localhost:8000$(NC)"

dev-local: setup serve-local ## Start development environment using host PHP server on :8001


# Cache management
cache-clear: ## Clear Symfony cache
	@echo "$(YELLOW)Clearing cache...$(NC)"
	$(CONSOLE) cache:clear --env=$(ENV)
	@echo "$(GREEN)Cache cleared!$(NC)"

cache-warmup: ## Warm up Symfony cache
	@echo "$(YELLOW)Warming up cache...$(NC)"
	$(CONSOLE) cache:warmup --env=$(ENV)
	@echo "$(GREEN)Cache warmed up!$(NC)"

# Database commands
db-create: ## Create database
	@echo "$(YELLOW)Creating database...$(NC)"
	$(CONSOLE) doctrine:database:create --if-not-exists
	@echo "$(GREEN)Database created!$(NC)"

db-migrate: ## Run database migrations
	@echo "$(YELLOW)Running database migrations...$(NC)"
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	@echo "$(GREEN)Migrations completed!$(NC)"

db-schema-update: ## Update database schema (force)
	@echo "$(YELLOW)Updating database schema...$(NC)"
	$(CONSOLE) doctrine:schema:update --force
	@echo "$(GREEN)Schema updated!$(NC)"

db-fixtures: ## Load database fixtures
	@echo "$(YELLOW)Loading database fixtures...$(NC)"
	$(CONSOLE) doctrine:fixtures:load --no-interaction
	@echo "$(GREEN)Fixtures loaded!$(NC)"

db-reset: ## Reset database (SQLite: delete file, then rebuild schema)
	@echo "$(YELLOW)Resetting database (SQLite)...$(NC)"
	rm -f var/data_$(ENV).db
	$(CONSOLE) doctrine:schema:update --force --env=$(ENV)
	@echo "$(GREEN)Database reset completed!$(NC)"

# Testing
test: ## Run tests
	@echo "$(YELLOW)Running tests...$(NC)"
	$(PHP) bin/phpunit
	@echo "$(GREEN)Tests completed!$(NC)"

# Cleanup
clean: ## Clean cache, logs and temporary files
	@echo "$(YELLOW)Cleaning project...$(NC)"
	rm -rf var/cache/*
	rm -rf var/log/*
	rm -rf var/sessions/*
	@echo "$(GREEN)Project cleaned!$(NC)"

# Quality assurance
cs-fix: ## Fix code style with PHP-CS-Fixer
	@echo "$(YELLOW)Fixing code style...$(NC)"
	$(PHP) vendor/bin/php-cs-fixer fix src
	@echo "$(GREEN)Code style fixed!$(NC)"

analyze: ## Run static analysis
	@echo "$(YELLOW)Running static analysis...$(NC)"
	$(PHP) vendor/bin/phpstan analyse src
	@echo "$(GREEN)Static analysis completed!$(NC)"

# Complete workflow commands
fresh: clean setup db-reset cache-clear ## Fresh installation (clean, setup, reset DB, clear cache)
	@echo "$(GREEN)Fresh installation completed!$(NC)"

deploy: install cache-clear cache-warmup ## Deploy application (install, clear cache, warm up)
	@echo "$(GREEN)Application deployed!$(NC)"

# Information
status: ## Show project status
	@echo "$(BLUE)Project Status:$(NC)"
	@echo "Environment: $(ENV)"
	@echo "PHP Version: $$($(PHP) -v | head -n 1)"
	@echo "Composer Version: $$($(COMPOSER) --version)"
	@echo "Docker Status: $$($(DOCKER_COMPOSE) ps --services 2>/dev/null | wc -l) services"
	@if [ -f .env ]; then \
		echo "Environment file: $(GREEN)Found$(NC)"; \
	else \
		echo "Environment file: $(RED)Missing$(NC)"; \
	fi