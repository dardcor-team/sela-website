# Stage 1 - Build Frontend (Vite)
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.4-fpm AS backend

ENV COMPOSER_ALLOW_SUPERUSER=1

# System dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    nginx\
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        intl \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Nginx config
RUN mkdir -p /etc/nginx/sites-available /etc/nginx/sites-enabled /run/php

COPY <<'EOF' /etc/nginx/sites-available/default
server {
    listen 8000;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

EXPOSE 8000

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]