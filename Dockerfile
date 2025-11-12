# ------------------------------
# Stage 1: Base PHP environment
# ------------------------------
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql gd zip mbstring exif pcntl bcmath opcache

# ------------------------------
# Stage 2: Composer installation
# ------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (optimized for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Expose the port Laravel's internal server will use
EXPOSE 8080

# ------------------------------
# Stage 3: Runtime
# ------------------------------
# Commands executed every time the container starts
CMD php artisan migrate --force \
    && php artisan config:clear \
    && php artisan cache:clear \
    && php artisan serve --host=0.0.0.0 --port=8080
