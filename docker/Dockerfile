FROM php:8.4-apache AS laravel_base

RUN apt-get update && apt-get install -y \
    git \
    g++ \
    zip \
    curl \
    sudo \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    supervisor \
    libjpeg-dev \
    libsodium-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    gd \
    zip \
    bz2 \
    intl \
    iconv \
    bcmath \
    sodium \
    opcache \
    calendar \
    pdo_pgsql \
    pdo_mysql

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev --optimize-autoloader

RUN a2enmod rewrite

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN cp docker/supervisor.conf /etc/supervisord.conf

RUN mv docker/php.ini "$PHP_INI_DIR/php.ini"

RUN chown -R www-data:www-data /var/www/html

RUN chmod +x /var/www/html/docker/docker-entrypoint.sh
RUN chmod +x /var/www/html/docker/schedule.sh

ENTRYPOINT [ "./docker/docker-entrypoint.sh" ]
