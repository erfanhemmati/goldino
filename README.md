# TLyn Gold Trading System

## Introduction

This project implements a gold trading platform where users can place buy and sell orders for gold. The system features:

- User registration and authentication
- Order placement and execution
- Partial order matching
- Fee calculation based on tiered rates
- Real-time balance updates

## Technology Stack

- **Backend**: PHP 8.2, Laravel 10
- **Database**: MySQL 8.0
- **Caching**: Redis
- **Infrastructure**: Docker

## Prerequisites

- Docker and Docker Compose
- Git

## Installation

1. Clone the repository:
```
git clone <repository-url>
cd tlyn
```

2. Copy the environment file:
```
cp .env.example .env
```

3. Build and start Docker containers:
```
docker-compose up -d
```

4. Install dependencies:
```
docker-compose exec app composer install
```

5. Generate application key:
```
docker-compose exec app php artisan key:generate
```

6. Run database migrations and seeders:
```
docker-compose exec app php artisan migrate --seed
```

## API Documentation

The system exposes the following REST API endpoints:

### Authentication

- `POST /api/register` - Register a new user
- `POST /api/login` - Login a user
- `POST /api/logout` - Logout a user

### Orders

- `GET /api/orders` - Get user's orders
- `POST /api/orders` - Create a new order
- `GET /api/orders/{order}` - Get a specific order
- `POST /api/orders/{order}/cancel` - Cancel an order

### Trades

- `GET /api/trades` - Get user's trades

### Balances

- `GET /api/balances` - Get user's balances
- `GET /api/balances/{coin}` - Get user's balance for a specific coin
- `POST /api/balances/update` - Update a user's balance (for testing)

## Testing

```
docker-compose exec app php artisan test
```

## Example Usage

### Creating an Order

```
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer {token}" -d '{
    "base_coin_id": 1,
    "quote_coin_id": 2,
    "type": "BUY",
    "amount": 2,
    "price": 10000000
}' http://localhost:8000/api/orders
```

## Features

1. **Tiered Fee Calculation**:
   - Up to 1 gram: 2%
   - From 1 to 10 grams: 1.5%
   - Above 10 grams: 1%
   - Minimum fee: 50,000 Tomans
   - Maximum fee: 5,000,000 Tomans

2. **Partial Order Matching**: Orders can be partially filled if there's not enough liquidity.

3. **FIFO Order Execution**: Orders are matched based on price and time priority.

4. **Optimized Performance**:
   - Redis caching for improved performance
   - Database transactions to ensure data integrity
   - Proper indexing for efficient queries
