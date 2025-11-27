# ====== BUILDER ======
FROM php:8.2-fpm-bullseye AS builder

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libicu-dev libzip-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    pkg-config g++ make autoconf curl nginx \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql intl mbstring xml zip curl

# MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier Symfony et installer dépendances
WORKDIR /app
COPY ./backend /app
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ====== PROD IMAGE ======
FROM php:8.2-fpm-bullseye

# Installer Nginx
RUN apt-get update && apt-get install -y nginx \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# Créer dossiers Symfony et permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html

# Copier config Nginx
COPY ./docker/default.conf /etc/nginx/sites-available/default

# Exposer le port
EXPOSE 80

# Lancer PHP-FPM + Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
