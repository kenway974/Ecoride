# ====== BUILDER ======
FROM php:8.2-fpm-bullseye AS builder

# Dépendances système nécessaires aux extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    pkg-config \
    g++ \
    make \
    autoconf \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP indispensables pour Symfony + Doctrine + JWT + Mercure
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    mbstring \
    xml \
    zip \
    curl

# Installer l’extension MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le backend et installer les dépendances PHP
WORKDIR /app
COPY ./backend /app
RUN composer install -vvv --no-dev --optimize-autoloader --no-interaction --no-scripts

# ====== PROD IMAGE ======
FROM php:8.2-fpm-bullseye

WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# Permissions
RUN chown -R www-data:www-data var/ vendor/

CMD ["php-fpm"]
