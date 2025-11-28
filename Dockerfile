FROM php:8.2-fpm-bullseye AS app

RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libicu-dev libzip-dev libonig-dev \
    libxml2-dev libcurl4-openssl-dev pkg-config g++ make autoconf curl

RUN docker-php-ext-install pdo pdo_pgsql intl mbstring xml zip curl
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./backend .
RUN vvv composer install --no-dev --optimize-autoloader --no-interaction

# Nginx inside the same container
RUN apt-get install -y nginx && rm -rf /var/lib/apt/lists/*
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p var/cache var/log && chown -R www-data:www-data .

EXPOSE 80
CMD service php-fpm start && nginx -g "daemon off;"
