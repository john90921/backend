FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy application files asa
COPY . /var/www/html

WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer require resend/resend-laravel fakerphp/faker laravel/pint laravel/sail mockery/mockery nunomaduro/collision pestphp/pest pestphp/pest-plugin-laravel
RUN composer install --no-dev --optimize-autoloader


CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000
