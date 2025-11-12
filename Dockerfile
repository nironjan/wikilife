# Use official PHP image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip mbstring exif pcntl bcmath opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files Here
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Ensure .env exists and app key is set
RUN php -r "if (!file_exists('.env')) copy('.env.example', '.env');" \
    && php artisan key:generate --force

# Expose port 8080
EXPOSE 8080

# Run migrations on startup and start the server
CMD php artisan migrate --force || true && \
    php artisan serve --host=0.0.0.0 --port=8080
