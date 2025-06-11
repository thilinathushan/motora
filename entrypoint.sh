#!/bin/bash

# Exit immediately if a command fails
set -e

APP_ENV=${APP_ENV:-production}

echo "🚀 EntryPoint Activated at $(date)"
echo "Starting container initialization..."

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "Composer is not installed. Please install Composer first."
    exit 1
fi

echo "Composer version: $(composer --version)"

# Install PHP dependencies
composer update barryvdh/laravel-dompdf
composer update kornrunner/keccak
composer update simplito/elliptic-php

composer validate

composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy .env.example to .env if it doesn't exist
# if [ ! -f /var/www/.env ]; then
#     cp /var/www/.env.example /var/www/.env
# fi
cp /var/www/.env.docker /var/www/.env

echo "🧪 Dumping Laravel DB config:"
php artisan tinker --execute="dump(config('database.connections.mysql'))"


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

echo "👤 Running as: $(whoami)"
php-fpm -tt || true  # test PHP-FPM config

# Start PHP-FPM
exec php-fpm -F
