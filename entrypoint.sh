#!/bin/bash

# Exit immediately if a command fails
set -e

APP_ENV=${APP_ENV:-production}

echo "Starting container initialization..."

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Check environment and build assets
if [ "$APP_ENV" == "production" ]; then
    echo "Production environment detected, building assets..."
    npm install
    npm run build
else
    echo "Development environment detected, starting Vite..."
    npm install
    npm run dev &
fi

# Start PHP-FPM
exec php-fpm
