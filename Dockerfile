ARG ALPINE_VERSION=3.21
FROM alpine:${ALPINE_VERSION}

WORKDIR /var/www/html

# Installer PHP, Nginx et superviseur
RUN apk add --no-cache \
    curl \
    nginx \
    php84 \
    php84-ctype \
    php84-curl \
    php84-dom \
    php84-fileinfo \
    php84-gd \
    php84-intl \
    php84-mbstring \
    php84-mysqli \
    php84-opcache \
    php84-openssl \
    php84-phar \
    php84-session \
    php84-tokenizer \
    php84-xml \
    php84-xmlreader \
    php84-xmlwriter \
    supervisor

RUN ln -s /usr/bin/php84 /usr/bin/php

# Copier configs Nginx & PHP-FPM & Supervisord
COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/conf.d /etc/nginx/conf.d/
COPY config/fpm-pool.conf /etc/php84/php-fpm.d/www.conf
COPY config/php.ini /etc/php84/conf.d/custom.ini
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Permissions
RUN chown -R nobody:nobody /var/www/html /run /var/lib/nginx /var/log/nginx

USER nobody

# Copier l’application Symfony
COPY --chown=nobody backend/ /var/www/html/

# Variables d’environnement Symfony pour Render
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Exposer le port
EXPOSE 8080

# Lancer PHP-FPM + Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Healthcheck
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping || exit 1
