FROM php:8.1-fpm-alpine

RUN apk add --no-cache curl libpq-dev libzip-dev unzip

RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY . /var/www