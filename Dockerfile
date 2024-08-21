# Use the official Laravel image
FROM composer:2 AS composer
WORKDIR /app
COPY . /app
RUN composer install

# Node image for frontend assets
FROM node:16 AS node
WORKDIR /app
COPY . /app
RUN npm install && npm run build

# Final PHP image
FROM php:8.1-fpm
WORKDIR /var/www/html
COPY --from=composer /app /var/www/html
COPY --from=node /app/public /var/www/html/public

# Install required extensions
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
