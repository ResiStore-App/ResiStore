FROM php:8.2-apache

# Allow composer run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install system deps
RUN apt-get update && apt-get install -y \
  libicu-dev \
  libzip-dev \
  zip \
  unzip \
  && docker-php-ext-install pdo pdo_mysql intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Give permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
