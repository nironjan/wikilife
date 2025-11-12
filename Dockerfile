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

# Ensure .env exists and app key is generated
RUN if [ ! -f .env ]; then \
        cp .env.example .env && \
        php artisan key:generate --force; \
    fi

# Expose the port Laravel's internal server will use
EXPOSE 8080

# ------------------------------
# Stage 3: Runtime
# ------------------------------
# On container start:
#  - Run migrations (ignore errors if already done)
#  - Seed admin user (if not exists)
#  - Clear caches
#  - Start Laravel server
CMD php artisan migrate --force || true && \
    php artisan db:seed --force || true && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=8080
