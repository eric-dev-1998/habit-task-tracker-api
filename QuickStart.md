# Quick start:

#### Features

1. Laravel REST API
2. Sanctum authentication
3. Tasks & habits management
4. Pagination, filtering, sorting
5. Mobile-ready JSON responses

#### API base URL: http://localhost:8000/api/v1

#### Postman collection: [Click to view](docs/Personal%20tracker.postman_collection.json)

## Project setup:

#### 1. Clone the repository.
#### 2. Copy environment file:
    cp .env.example .env
#### 3. Install dependencies:
    composer install
#### 4. Generate key:
    php artisan key:generate
#### 5. Run migrations:
    php artisan migrate
#### 6. Start server:
    php artisan serve
