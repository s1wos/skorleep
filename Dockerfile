FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    build-essential libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git curl \
    libzip-dev libpq-dev libmcrypt-dev libssl-dev gnupg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Установка Node.js 20 и npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

# Копируем проект
COPY . .

# Установка зависимостей Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Доступ к storage и bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
