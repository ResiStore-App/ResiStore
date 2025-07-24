FROM php:8.3-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
  libicu-dev \
  libzip-dev \
  zip \
  unzip \
  && docker-php-ext-install pdo pdo_mysql intl zip

# Enable Apache Rewrite
RUN a2enmod rewrite

# Set DocumentRoot ke public
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
