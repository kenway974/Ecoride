# ====== BUILDER ======
FROM php:8.2-fpm-bullseye AS builder

# Installer les outils et dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    g++ \
    make \
    autoconf \
    pkg-config \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY ./backend /app

# Installer les extensions PHP une par une pour plus de fiabilité
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installer et activer MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# ====== PROD IMAGE ======
FROM php:8.2-fpm-bullseye

WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# Permissions
RUN chown -R www-data:www-data var/ vendor/

CMD ["php-fpm"]
