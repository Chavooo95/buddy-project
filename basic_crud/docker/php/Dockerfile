FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libssl-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install MongoDB PHP extension (2.x - matches the version setup-php installs in CI)
RUN pecl install mongodb-2.5.2 \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]