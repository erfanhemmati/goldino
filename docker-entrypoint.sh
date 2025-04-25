#!/usr/bin/env bash
set -e

cd /var/www/html

# Wait for database to be available
until mysqladmin ping -h "${DB_HOST:-mysql}" --silent; do
    echo "Waiting for database..."
    sleep 3
done

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --ansi
fi

if [ "$MIGRATE" = "true" ]; then
    php artisan migrate --force --seed
fi

exec "$@"
