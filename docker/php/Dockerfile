FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev vim sudo

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG UID=1000
ARG GID=1000

RUN usermod -u ${UID} www-data \
    && groupmod -g ${GID} www-data

RUN chown -R www-data:www-data /var/www

USER www-data

WORKDIR /var/www/html
