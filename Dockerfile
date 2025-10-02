FROM composer:2 as vendor

WORKDIR /app

# Copy only composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies without executing scripts (they require runtime env)
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader

# Copy application code to allow classmap optimization in later stage
COPY . /app

###############################################
# Build frontend assets
###############################################
FROM node:20-alpine as assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY vite.config.js ./vite.config.js
COPY public ./public
# Build Vite assets into public/build
RUN npm run build

###############################################
# Runtime image with PHP-FPM, Nginx, Supervisor
###############################################
FROM php:8.2-fpm-bullseye as runtime

ENV DEBIAN_FRONTEND=noninteractive \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    PHP_MEMORY_LIMIT=512M \
    PHP_MAX_EXECUTION_TIME=120 \
    PHP_UPLOAD_MAX_FILESIZE=20M \
    PHP_POST_MAX_SIZE=20M

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libicu-dev \
        locales \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions commonly needed by Laravel and dompdf
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo_mysql \
        zip \
        exif \
        intl \
        opcache

# Configure PHP
RUN { \
      echo "memory_limit=${PHP_MEMORY_LIMIT}"; \
      echo "max_execution_time=${PHP_MAX_EXECUTION_TIME}"; \
      echo "opcache.enable=1"; \
      echo "opcache.enable_cli=1"; \
      echo "opcache.jit=1255"; \
      echo "opcache.jit_buffer_size=64M"; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

# Copy composer vendor from vendor stage
COPY --from=vendor /app/vendor ./vendor

# Copy application source
COPY . .

# Copy built assets
COPY --from=assets /app/public/build ./public/build

# Nginx and Supervisor configuration
COPY docker/nginx/nginx.conf/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entry script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Ensure proper permissions for Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwX storage bootstrap/cache

# Send Nginx logs to stdout/stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

ENV PORT=8080
EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]


