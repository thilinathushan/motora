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

role=${1:-app} # Default to 'app' if no role is provided

if [ "$role" = "app" ]; then
    # --- ROLE-SPECIFIC SETUP (Runs only for the App container) ---

    echo "Running APP container setup..."

    # Run migrations, generate keys, and perform first-time setup.
    # The worker container does not need to do this.
    php artisan migrate --force
    php artisan key:generate --force
    php artisan storage:link

    # The initialization lock file logic
    INIT_LOCK_FILE="/var/www/storage/app/.initialized"
    if [ ! -f "$INIT_LOCK_FILE" ]; then
        echo "🌱 First-time setup: Seeding database..."
        php artisan db:seed --force
        touch "$INIT_LOCK_FILE"
        echo "✅ Seeding complete. Lock file created."
    fi

    # For production, it's best to cache everything after all setup is done.
    echo "Caching configuration for production..."
    php artisan optimize

    # --- ROLE-SPECIFIC EXECUTION ---
    echo "Initialization complete. Starting PHP-FPM..."
    exec php-fpm

elif [ "$role" = "worker" ]; then
    # --- ROLE-SPECIFIC EXECUTION (Runs only for the Worker container) ---

    echo "Running WORKER container setup..."
    # A worker just needs the config to be ready.
    php artisan config:cache

    echo "Initialization complete. Starting Supervisor..."
    exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf

else
    # Allow running other arbitrary commands
    echo "Running custom command: $@"
    exec "$@"
fi

# --- END OF ARTISAN COMMANDS ---

echo "👤 Running as: $(whoami)"
echo "Initialization complete. Starting PHP-FPM..."
exec php-fpm