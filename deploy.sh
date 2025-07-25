#!/usr/bin/env bash

# Start PHP-FPM di background
php-fpm &

# Install composer deps
echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader

# Set folder permissions
echo "Setting folder permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buat storage link
echo "Creating storage symlink..."
php artisan storage:link || true

# Cache config & routes
echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache


# Jalankan nginx
echo "Starting nginx on 0.0.0.0:10000..."
nginx -g 'daemon off;'
