FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libssl-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install MongoDB PHP extension (1.x - compatible with doctrine/mongodb-odm-bundle ^5)
RUN pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]