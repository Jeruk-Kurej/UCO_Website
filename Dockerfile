FROM php:8.4-cli

# Force rebuild - PHP 8.4 required
RUN php -v

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (including zip)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies (production only) - skip scripts to avoid artisan errors
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copy package files
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm ci

# Copy application files
COPY . .

# Build assets with Vite (generates /public/build/)
RUN npm run build && \
    echo "=== Vite Build Complete ===" && \
    ls -la public/build/ && \
    cat public/build/manifest.json

# Now run composer scripts after files are copied
RUN composer dump-autoload --optimize

# Run Laravel package discovery
RUN php artisan package:discover --ansi

# Set permissions
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Expose port (will be overridden by Railway's PORT env var)
EXPOSE 8000

# Start server - make migrations optional to prevent startup failure
CMD echo "=== RAILWAY STARTUP ===" && \
    echo "PORT: ${PORT:-8000}" && \
    echo "APP_ENV: ${APP_ENV:-not_set}" && \
    echo "APP_DEBUG: ${APP_DEBUG:-not_set}" && \
    echo "=== Checking Vite manifest ===" && \
    test -f public/build/manifest.json && echo "Manifest exists" || echo "Manifest MISSING!" && \
    cat public/build/manifest.json && \
    echo "" && \
    echo "=== Starting PHP Server ===" && \
    php -S 0.0.0.0:${PORT:-8000} -t public
