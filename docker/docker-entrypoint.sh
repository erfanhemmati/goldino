#!/bin/bash

set -xe

chown -R www-data:www-data ./storage/logs


if [ "$CONTAINER_ROLE" == "apache2" ]; then
    echo "Running Laravel migrations..."
    sleep 10
    php /var/www/html/artisan migrate --force --seed

    echo "Clearing Laravel cache..."
    php /var/www/html/artisan optimize:clear
    php /var/www/html/artisan storage:link
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache
    php /var/www/html/artisan view:cache

    echo "Starting Apache2 service..."
    exec apache2-foreground

elif [ "$CONTAINER_ROLE" == "queue" ]; then
    echo "Starting Laravel Queue Worker..."
    exec /usr/bin/supervisord -c /etc/supervisord.conf

else
    echo "Please set the CONTAINER_ROLE environment variable to one of: apache2, queue"
    exit 1
fi
