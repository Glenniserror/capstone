# === STAGE 1: Build Frontend Assets ===
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# === STAGE 2: Production PHP Environment ===
FROM php:8.3-cli

# Mag-install ng system dependencies kasama ang GD extension (para sa mga larawan/charts kung mayroon man)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# Kopyahin ang pinakabagong Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Kopyahin ang buong source code ng Laravel
COPY . .

# Kopyahin ang binuong Vite assets mula sa frontend stage
COPY --from=frontend /app/public/build ./public/build

# I-install ang mga PHP dependencies na optimized para sa production
RUN composer install --no-dev --optimize-autoloader

# 🛠️ CRITICAL: Siguraduhing may karapatang magsulat ang Laravel sa storage at cache
RUN chmod -R 775 storage bootstrap/cache

# Gagamitin ni Render ang dynamic $PORT, pero ligtas mag-set ng default para sa local testing
EXPOSE 8080

# Patakbuhin ang built-in server gamit ang shell form para mabasa ang $PORT variable ng Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}