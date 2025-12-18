FROM php:8.2-cli

WORKDIR /var/www

# Install dependencies + PHP extensions
RUN apt-get update -yqq && apt-get install -yqq \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath xml pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Buat database folder + file SQLite
RUN mkdir -p database && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

