# ---------- STAGE 1 : BUILD FRONT (NODE) ----------
FROM node:18-alpine AS node-build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# BUILD FRONT (si tu as un build front: ex. npm run build)
RUN npm run build || echo "no frontend build script"

# ---------- STAGE 2 : PHP + LARAVEL ----------
FROM php:8.2-fpm

# INSTALL DEPENDANCES SYSTEME
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    netcat \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# INSTALL COMPOSER
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# COPIE DU PROJET (vendor et node_modules exclus via .dockerignore)
COPY . .

# INSTALL DEPENDANCES PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# COPIE DES ASSETS BUILDÉS DEPUIS LE STAGE NODE (SI EXISTE)
COPY --from=node-build /app/dist ./public/dist || true
COPY --from=node-build /app/build ./public/build || true

# PERMISSIONS
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 8000

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
