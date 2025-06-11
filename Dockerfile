# ---- Stage 1: Build Stage ----
# Use a full-featured image to build our assets
FROM php:8.2-fpm as builder

# Install system dependencies for building
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libgmp-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gmp

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy source code
COPY . .

# Install composer dependencies for production
# This creates the /vendor directory
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Build frontend assets
# This creates the /public/build directory
RUN npm install
RUN npm run build

# ---- Stage 2: Final Production Image ----
# Start from a clean PHP-FPM image
FROM php:8.2-fpm

# Arguments for user
ARG user=www-data
ARG uid=1000

# Install ONLY the required PHP extensions for running the app
RUN apt-get update && apt-get install -y \
    # Dependencies for your PHP extensions
    libpng-dev \
    libxml2-dev \
    libgmp-dev \
    libonig-dev \
    gd \
    libjpeg-dev \
    libfreetype6-dev \
    # General utilities
    zip \
    unzip \
    --no-install-recommends \
    # Now, compile the PHP extensions
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gmp \
    # Finally, clean up the build dependencies to reduce image size
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
       libpng-dev libxml2-dev libgmp-dev libonig-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Create a non-root user
RUN useradd -m -u $uid -s /bin/bash $user

WORKDIR /var/www

# Copy the built application files from the builder stage
COPY --from=builder --chown=$user:$user /var/www/vendor ./vendor
COPY --from=builder --chown=$user:$user /var/www/public ./public
COPY --from=builder --chown=$user:$user /var/www/app ./app
COPY --from=builder --chown=$user:$user /var/www/bootstrap ./bootstrap
COPY --from=builder --chown=$user:$user /var/www/config ./config
COPY --from=builder --chown=$user:$user /var/www/database ./database
COPY --from=builder --chown=$user:$user /var/www/resources ./resources
COPY --from=builder --chown=$user:$user /var/www/routes ./routes
COPY --from=builder --chown=$user:$user /var/www/storage ./storage
COPY --from=builder --chown=$user:$user /var/www/artisan .
COPY --from=builder --chown=$user:$user /var/www/composer.json .
COPY --from=builder --chown=$user:$user /var/www/composer.lock .

# Copy the simplified entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set permissions
RUN chown -R $user:$user /var/www/storage /var/www/bootstrap/cache

USER $user

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]