# Stage 1: Composer for Laravel dependencies
FROM composer:2 AS composer
WORKDIR /app
COPY . /app
RUN composer install --prefer-dist --no-dev --no-scripts --no-interaction --optimize-autoloader

# Stage 2: Node for frontend assets
FROM node:18 AS node
WORKDIR /app
COPY . /app
RUN npm install && npm run build

# Stage 3: Final PHP image
FROM php:8.3-fpm
WORKDIR /var/www/html

# Copy Laravel application files
COPY --from=composer /app /var/www/html
COPY --from=node /app/public /var/www/html/public

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql


# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
