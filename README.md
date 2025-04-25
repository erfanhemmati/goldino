# Goldino Gold Trading API

A RESTful API for gold trading between users with order matching functionality developed using Laravel.

## Features

- User authentication system
- Gold trading with buy/sell order matching
- Balance management for users
- Fee calculation based on tiered pricing
- Transaction history tracking
- RESTful API architecture

## System Requirements

- Docker and Docker Compose

## Tech Stack

- **Backend Framework**: Laravel 
- **Database**: MySQL 8.0
- **Caching**: Redis
- **Web Server**: Nginx
- **PHP Version**: 8.2

## Installation

### Using Docker (Recommended)

1. Clone the repository:
   ```
   git clone git@github.com:erfanhemmati/goldino.git
   cd goldino
   ```

2. Configure environment variables:
   - Optionally copy the example env file:
     ```
     cp .env.example .env
     ```
   - Update settings as needed.
   - The Docker development environment file is located at `docker/development/.local.env`. Modify it if needed.

3. Build the Docker image:
   ```
   docker build -t goldino -f docker/Dockerfile .
   ```

4. Start the development environment:
   ```
   docker compose -f docker/development/compose.yml up -d
   ```

5. (First-time only) Install PHP dependencies inside the `goldino-api` container:
   ```
   docker compose -f docker/development/compose.yml exec goldino-api composer install --optimize-autoloader --no-dev
   ```

6. The application will automatically run migrations, seed the database, and cache configuration on container startup.

7. Access the application at http://127.0.0.1:8000.

(Optional) To optimize performance, cache the configuration and routes:
```
docker compose -f docker/development/compose.yml exec goldino-api php artisan config:cache
docker compose -f docker/development/compose.yml exec goldino-api php artisan route:cache
```

### Manual Installation

If you prefer to install without Docker:

1. Ensure PHP 8.2, Composer, MySQL 8.0, and Redis are installed on your system
2. Follow steps 1-3 from the Docker installation
3. Install dependencies: `composer install`
4. Generate application key: `php artisan key:generate`
5. Run migrations and seeders: `php artisan migrate --seed`
6. Optimize the application:
   ```
   php artisan config:cache
   php artisan route:cache
   ```
7. Start the development server: `php artisan serve`

## API Documentation

### Authentication
- **POST /api/v1/register** - Register a new user
- **POST /api/v1/login** - Log in and receive an API token
- **POST /api/v1/logout** - Log out and invalidate token

### Balances
- **GET /api/v1/balances** - Get all user balances
- **GET /api/v1/balances/{coinId}** - Get specific coin balance

### Orders
- **GET /api/v1/orders** - Get all user orders
- **POST /api/v1/orders** - Create a new buy/sell order
- **POST /api/v1/orders/{id}/cancel** - Cancel an open order

### Trades
- **GET /api/v1/trades** - Get all user trades history

## Fee Structure

The system applies a tiered fee structure to trades:
- Up to 1 gram: 2% fee
- From 1 to 10 grams: 1.5% fee
- Over 10 grams: 1% fee
- Minimum fee: 50,000 tomans
- Maximum fee: 5,000,000 tomans

## License

[MIT License](LICENSE)

## Contributors

- Erfan Hemmati

## Support

For support, contact e.hemmati.19@gmail.com
