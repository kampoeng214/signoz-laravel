#!/bin/sh
apt-get update
apt-get install -y unzip git curl libpng-dev libonig-dev libxml2-dev sqlite3
docker-php-ext-install pdo pdo_mysql mbstring bcmath xml pdo_sqlite
php artisan key:generate --force
php artisan config:clear
php artisan cache:clear
php artisan serve --host=0.0.0.0 --port=8000

