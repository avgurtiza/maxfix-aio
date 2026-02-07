FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite \
    sqlite-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create Laravel user
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Change ownership
RUN chown -R laravel:laravel /var/www/html

# Switch to laravel user
USER laravel

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
