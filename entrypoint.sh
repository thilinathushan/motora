#!/bin/bash

# Exit immediately if a command fails
set -e

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

echo "Waiting for database to be ready..."
# Use a loop to try connecting to the database.
# The `mysqladmin ping` command is perfect for this. It's a lightweight check.
# The `-h` flag is for the host, `-u` for user, `-p` for password.
# We redirect output to /dev/null to keep the logs clean.
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "Database is ready!"

# Now that .env exists, we can run artisan commands
# Clear any old cached config that might not have the .env file
php artisan config:clear
php artisan cache:clear

# Generate the application key. This will write to the .env file we just created.
php artisan key:generate --force

# Cache the configuration for performance
php artisan config:cache

# Run database migrations
php artisan migrate --force

# Create the storage link
php artisan storage:link

echo "👤 Running as: $(whoami)"

# Start PHP-FPM as the main process
echo "Initialization complete. Starting PHP-FPM..."
exec php-fpm
