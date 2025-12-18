cat Dockerfile 
# Stage 1: Build composer dependencies
FROM php:8.2-fpm-bullseye AS builder

WORKDIR /var/www

# Install dependency OS + extension PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libzip4 \
    zip unzip \
    pkg-config \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install OpenTelemetry PHP Extensiondd
RUN pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Production
FROM php:8.2-fpm-bullseye

WORKDIR /var/www


RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    libzip4 \
    zip unzip \
    pkg-config \
    && docker-php-ext-install pdo pdo_mysql zip

# Install OpenTelemetry PHP Extension
RUN pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry

# Set OTEL Environment Variables
ENV OTEL_PHP_AUTOLOAD_ENABLED=true
ENV OTEL_SERVICE_NAME=laravel-otel
ENV OTEL_EXPORTER_OTLP_ENDPOINT=http://10.190.13.52:4318
ENV OTEL_TRACES_EXPORTER=otlp
ENV OTEL_METRICS_EXPORTER=none
ENV OTEL_LOGS_EXPORTER=none


# Copy app results from builder
COPY --from=builder /var/www /var/www

# Copy nginx config
COPY ./docker/nginx.conf /etc/nginx/sites-enabled/default

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 777 database storage bootstrap/cache


EXPOSE 80

CMD service nginx start && php-fpm
