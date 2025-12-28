# Buddy Project

A PHP backend project aimed at providing basic CRUD functionality and API endpoints.  
This repository currently contains essential routes and structure but still needs improvements and enhancements, including database connection handling and constructor refactoring.

## 🚀 Features

✔ Basic CRUD operations  
✔ Docker support with Dockerfile  
✔ Structured PHP backend

## 📌 Requirements

Make sure you have the following installed before running the project:

- PHP 8.x or higher  
- Composer  
- MySQL and/or MongoDB  
- Docker

## 🛠 Installation

1. Clone the repository  
   ```bash
   git clone https://github.com/Chavooo95/buddy-project.git
   ```

2. Install dependencies  
   ```bash
   composer install
   ```

3. Configure your environment variables  
   Copy `.env.example` to `.env` and fill in your database credentials (MySQL / MongoDB).

4. Run the project  
   ```bash
   php -S localhost:8000 -t public
   ```

   Or using Docker (if configured):
   ```bash
   docker build -t buddy-project .
   docker run -p 8000:80 buddy-project
   ```

## 📌 Task List

### ✅ Current Tasks
- [ ] Try to connect and unify endpoints for both MySQL and MongoDB  
- [ ] Create and standardize the project `__construct` methods to improve initialization and dependency injection  

## 📎 Endpoints

_This section will be filled once the endpoints are fully documented._

## 📦 Project Structure

```
.
├── app/
├── public/
├── routes/
├── composer.json
├── Dockerfile
└── README.md
```