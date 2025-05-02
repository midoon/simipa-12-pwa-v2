FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"


WORKDIR /app

COPY . /app

RUN apt update && apt install zip libzip-dev -y && \
    docker-php-ext-install zip pdo pdo_mysql && \
    docker-php-ext-enable zip pdo_mysql


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

# Install Node.js & Vite dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs && \
    npm install && npm run build
