#!/bin/bash

# Exit immediately if a command fails
set -e

# First, check if the vendor directory has been initialized in the volume.
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "NO VENDOR DETECTED: Performing first-time code initialization..."
    # If autoload.php is missing, it's a fresh or corrupted volume.
    # Copy EVERYTHING from the pristine source, including the vendor directory.
    rsync -a /var/www-pristine/ /var/www/
    echo "✅ Full code sync complete."
else
    echo "VENDOR DETECTED: Performing code update (excluding vendor)..."
    # If autoload.php exists, it's a subsequent deployment.
    # Sync the app code but leave the vendor directory untouched to preserve it.
    rsync -a --delete \
        --exclude 'vendor' \
        --exclude 'public/build' \
        --exclude 'storage' \
        /var/www-pristine/ /var/w/ww/
    echo "✅ Application code sync complete."
fi

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

role=${1:-app}

if [ "$role" = "app" ]; then
    echo "Running APP container setup..."
    # 1. ALWAYS run migrations on every deployment.
    #    Laravel is smart enough to only run new ones.
    echo "Running database migrations..."
    php artisan migrate --force

    # 2. Link storage (safe to re-run).
    php artisan storage:link

    # 3. Handle first-time-only setup using the lock file.
    INIT_LOCK_FILE="/var/www/storage/app/.initialized"
    if [ ! -f "$INIT_LOCK_FILE" ]; then
        echo "🌱 First-time setup detected..."

        echo "Generating application key..."
        php artisan key:generate --force

        echo "Seeding database..."
        php artisan db:seed --force

        echo "Creating initialization lock file..."
        touch "$INIT_LOCK_FILE"
        echo "✅ First-time setup complete."
    fi

    # 4. ALWAYS cache configuration for production performance.
    echo "Caching configuration for production..."
    php artisan optimize

    # 5. Start the web server.
    echo "Initialization complete. Starting PHP-FPM..."
    exec php-fpm

elif [ "$role" = "worker" ]; then
    echo "Running WORKER container setup..."
    # The worker just needs to ensure caches are up-to-date.
    php artisan optimize

    echo "Initialization complete. Starting Supervisor..."
    exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf

else
    echo "Running custom command: $@"
    exec "$@"
fi