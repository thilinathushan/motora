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

ARG CACHE_BUSTER
LABEL cache_buster=${CACHE_BUSTER}

# Copy source code
COPY . .

# Install composer dependencies for production
# This creates the /vendor directory
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Build frontend assets
# This creates the /public/build directory
RUN npm install
RUN echo "APP_URL=${APP_URL}" > .env
RUN npm run build

# ---- Stage 2: Final Production Image ----
# Start from a clean PHP-FPM image
FROM php:8.2-fpm

# Arguments for user
ARG user=www-data
ARG uid=1000

# Install runtime system libs AND temporary build dependencies in a single layer
RUN apt-get update && apt-get install -y \
    # RUNTIME libraries (these will stay)
    libgmp10 \
    libpng16-16 \
    libjpeg62-turbo \
    libfreetype6 \
    zip \
    unzip \
    default-mysql-client \
    supervisor \
    rsync \
    # BUILD dependencies (these will be removed)
    libgmp-dev \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libgd-dev \
    --no-install-recommends \
    \
    # Configure and install PHP extensions
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gmp \
    \
    # Clean up ONLY the build dependencies to make the final image smaller
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
       libgmp-dev libpng-dev libxml2-dev libonig-dev libjpeg-dev libfreetype6-dev libgd-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Create a non-root user
RUN groupmod -g $uid $user && \
    usermod -u $uid -d /var/www $user

WORKDIR /var/www

COPY docker-configs/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

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
COPY --from=builder --chown=$user:$user /var/www/.env.example .

# Copy the simplified entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY --from=builder --chown=$user:$user . /var/www-pristine

# Set permissions
RUN chown -R $user:$user /var/www

RUN mkdir -p /var/log/supervisor
RUN chown -R $user:$user /var/log/supervisor

USER $user

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]