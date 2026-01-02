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
EXPOSE 8080

# Create startup script that waits for PORT
RUN echo '#!/bin/sh\n\
set -e\n\
echo "=== RAILWAY STARTUP DEBUG ==="\n\
echo "Waiting for PORT environment variable..."\n\
while [ -z "$PORT" ]; do\n\
  echo "PORT not set yet, waiting 1 second..."\n\
  sleep 1\n\
done\n\
echo "PORT detected: $PORT"\n\
echo "APP_ENV: $APP_ENV"\n\
echo "APP_DEBUG: $APP_DEBUG"\n\
echo "Current time: $(date)"\n\
echo "PHP version: $(php -v | head -n 1)"\n\
echo "Starting Laravel server on 0.0.0.0:$PORT"\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
exec php artisan serve --host=0.0.0.0 --port=$PORT --verbose\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]
