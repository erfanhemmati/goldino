#!/bin/bash

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}Setting up Goldino Gold Trading API with Docker...${NC}"

# Check if docker is installed
if ! [ -x "$(command -v docker)" ]; then
  echo -e "${YELLOW}Docker is not installed. Please install Docker first.${NC}"
  exit 1
fi

# Check if docker-compose is installed
if ! [ -x "$(command -v docker compose)" ]; then
  echo -e "${YELLOW}Docker Compose is not installed. Please install Docker Compose first.${NC}"
  exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
  echo -e "${GREEN}Creating .env file...${NC}"
  cp .env.example .env
  echo -e "${YELLOW}Please update your .env file with proper configuration.${NC}"
  echo -e "${YELLOW}Press any key to continue when done...${NC}"
  read -n 1 -s
fi

# Create Docker directories if they don't exist
echo -e "${GREEN}Creating Docker configuration directories...${NC}"
mkdir -p docker/nginx/conf.d docker/php docker/mysql/data

# Build and start containers
echo -e "${GREEN}Building and starting Docker containers...${NC}"
docker compose up -d --build

# Install composer dependencies
echo -e "${GREEN}Installing composer dependencies...${NC}"
docker compose exec app composer install

# Generate application key
echo -e "${GREEN}Generating application key...${NC}"
docker compose exec app php artisan key:generate

# Run migrations and seeders
echo -e "${GREEN}Running database migrations and seeders...${NC}"
docker compose exec app php artisan migrate:fresh --seed

# Cache configuration
echo -e "${GREEN}Optimizing Laravel...${NC}"
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache

echo -e "${GREEN}Setup completed successfully!${NC}"
echo -e "${GREEN}The application is now available at http://localhost:8000${NC}"
