#!/bin/bash

# Exit immediately if a command fails
set -e

# Generate .env file from environment variables passed by Docker
echo "Generating .env file..."
# Use a heredoc to write the file. The variables like $APP_NAME will be
# substituted by the shell with the environment variables.
cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"

LOG_CHANNEL="${LOG_CHANNEL}"
LOG_DEPRECATIONS_CHANNEL="${LOG_DEPRECATIONS_CHANNEL}"
LOG_LEVEL="${LOG_LEVEL}"

DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

EOF

echo ".env file created successfully."

echo "🧪 Dumping Laravel DB config:"

echo "🚀 EntryPoint Activated at $(date)"
echo "Starting container initialization..."

# Check if Composer is installed
# if ! command -v composer &> /dev/null; then
#     echo "Composer is not installed. Please install Composer first."
#     exit 1
# fi

# echo "Composer version: $(composer --version)"

# # Install PHP dependencies
# composer update barryvdh/laravel-dompdf
# composer update kornrunner/keccak
# composer update simplito/elliptic-php

# composer validate

# composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy .env.example to .env if it doesn't exist
# if [ ! -f /var/www/.env ]; then
#     cp /var/www/.env.example /var/www/.env
# fi
# cp /var/www/.env.docker /var/www/.env

# echo "🧪 Dumping Laravel DB config:"
# php artisan tinker --execute="dump(config('database.connections.mysql'))"

php artisan optimize

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create the storage link CORRECTLY inside the container
echo "Creating storage link..."
php artisan storage:link

# # Check environment and build assets
# if [ "$APP_ENV" == "production" ]; then
#     echo "Production environment detected, building assets..."
#     npm install
#     npm run build
# else
#     echo "Development environment detected, starting Vite..."
#     npm install
#     npm run dev &
# fi

echo "👤 Running as: $(whoami)"
php-fpm -tt || true  # test PHP-FPM config

# Start PHP-FPM
exec php-fpm -F
