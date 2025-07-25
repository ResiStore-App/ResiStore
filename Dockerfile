FROM php:8.3-fpm

# Install system dependencies, tambahkan libonig-dev
RUN apt-get update && apt-get install -y \
  nginx \
  zip unzip curl git libpng-dev libjpeg-dev libfreetype6-dev \
  libicu-dev libxml2-dev libzip-dev \
  sqlite3 libsqlite3-dev \
  libonig-dev \
  pkg-config

# Install PHP extensions
RUN docker-php-ext-install intl pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy source code
WORKDIR /var/www/html
COPY . /var/www/html

# Copy custom nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Give permissions
RUN chown -R www-data:www-data /var/www/html

# Fix storage & cache permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy deploy script
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

# Expose port Render default
EXPOSE 10000

# Start deploy script
CMD ["bash", "/usr/local/bin/deploy.sh"]
