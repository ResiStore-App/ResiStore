FROM php:8.3-fpm-alpine

# Install system deps
RUN apk add --no-cache nginx curl bash git unzip supervisor \
  icu-dev zlib-dev libzip-dev libpng-dev libjpeg-turbo-dev oniguruma-dev \
  && docker-php-ext-install intl zip pdo pdo_mysql mbstring gd

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setup working dir
WORKDIR /var/www/html

# Copy app source
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Add simple process manager (no s6)
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Expose ports
EXPOSE 80

# Run Nginx + PHP-FPM
CMD ["/start.sh"]
