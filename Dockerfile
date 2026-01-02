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

# Build assets
RUN npm run build

# Now run composer scripts after files are copied
RUN composer dump-autoload --optimize

# Run Laravel package discovery
RUN php artisan package:discover --ansi

# Set permissions
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Expose port (Railway will override with $PORT)
EXPOSE 8000

# Start application - use Railway's runtime PORT variable
CMD echo "=== RAILWAY STARTUP DEBUG ===" && \
    echo "PORT variable: $PORT" && \
    echo "APP_ENV: $APP_ENV" && \
    echo "APP_DEBUG: $APP_DEBUG" && \
    echo "Current time: $(date)" && \
    echo "PHP version: $(php -v | head -n 1)" && \
    echo "Starting Laravel server on 0.0.0.0:$PORT" && \
    php artisan serve --host=0.0.0.0 --port=$PORT --verbose
