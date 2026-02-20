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
    sqlite-dev \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_sqlite \
    pdo_pgsql \
    intl \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash
ENV BUN_INSTALL=/root/.bun
ENV PATH=$BUN_INSTALL/bin:$PATH

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create Laravel user
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install JS dependencies and build assets
RUN if [ -f package.json ]; then bun install && bun run build; fi

# Change ownership
RUN chown -R laravel:laravel /var/www/html

# Switch to laravel user
USER laravel

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
