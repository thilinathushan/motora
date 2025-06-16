#!/bin/bash

# Exit immediately if a command fails
set -e

echo "🚀 EntryPoint Activated at $(date)"

# Use the existing .env.example as the template
if [ ! -f /var/www/.env.example ]; then
    echo "ERROR: .env.example file not found!"
    exit 1
fi

echo "Generating .env file from .env.example and environment variables..."

# Create a fresh .env file
> /var/www/.env

# Read the .env.example file, filter out comments and blank lines,
# and then build the final .env file.
grep -v -e '^#' -e '^\s*$' /var/www/.env.example | while IFS= read -r line; do
    # Get the variable name (everything before the '=')
    var_name=$(echo "$line" | cut -d '=' -f 1)
    
    # Get the value of that variable from the environment.
    # The `${!var_name}` syntax is bash for indirect expansion.
    var_value="${!var_name}"

    # Write "VAR_NAME=VAR_VALUE" to the .env file
    echo "$var_name=$var_value" >> /var/www/.env
done

echo ".env file created successfully."

# --- WAIT FOR DATABASE SECTION ---
echo "Waiting for database to be ready..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "Database is ready!"
# --- END: WAIT FOR DATABASE SECTION ---

# 1. Run migrations FIRST to create all tables.
echo "Running database migrations..."
php artisan migrate --force

# 2. Now that tables exist, we can safely clear caches.
echo "Clearing application caches..."
php artisan optimize:clear

# 3. Generate key and cache config for production performance.
echo "Generating key and caching config..."
php artisan key:generate --force
php artisan optimize

# 4. Link storage.
echo "Creating storage link..."
php artisan storage:link

# 5. Run any additional Artisan commands
echo "Running Database Seeder..."
php artisan db:seed

# --- END OF ARTISAN COMMANDS ---

echo "👤 Running as: $(whoami)"
echo "Initialization complete. Starting PHP-FPM..."
exec php-fpm