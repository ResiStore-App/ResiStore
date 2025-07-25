#!/usr/bin/env bash

set -e

# Start PHP-FPM background
php-fpm &

# Install composer dependencies
echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader

# Fix folder permissions
echo "Setting storage & cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate storage symlink
echo "Creating storage symlink..."
php artisan storage:link || true

# Generate APP_KEY kalau belum
if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi

if ! grep -q "APP_KEY=" /var/www/html/.env; then
  echo "Generating APP_KEY..."
  php artisan key:generate
fi

# Cache config & routes
echo "Caching config & routes..."
php artisan config:cache
php artisan route:cache

# (Optional) Start queue worker in background
# echo "Starting queue worker..."
# php artisan queue:work --daemon &

# Start nginx in foreground
echo "Starting nginx on 0.0.0.0:10000..."
nginx -g 'daemon off;'
