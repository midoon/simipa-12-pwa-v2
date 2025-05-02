FROM php:8.3-cli

WORKDIR /app

COPY --chown=www-data:www-data . /app

RUN apt update && apt install zip libzip-dev -y && \
    docker-php-ext-install zip pcntl pdo pdo_mysql && \
    docker-php-ext-enable zip pdo_mysql


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install && \
    composer require laravel/octane && \
    php artisan octane:install --server=frankenphp


# Install Node.js & Vite dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs && \
    npm install && npm run build

EXPOSE 8000

CMD php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000
