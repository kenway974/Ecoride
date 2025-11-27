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

# Extensions PHP
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
FROM nginx:1.25-alpine

# Créer l'utilisateur www-data
RUN addgroup -g 82 -S www-data \
    && adduser -u 82 -D -S -G www-data www-data

# Copier le backend 
COPY --from=builder /app /var/www/html

# Copier la config Nginx
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

# Créer les dossiers / permissions
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html \
    && chown -R nginx:nginx /var/www/html

# Exposer le port
EXPOSE 80

# Lancer Nginx
CMD ["nginx", "-g", "daemon off;"]