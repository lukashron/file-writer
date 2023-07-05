FROM php:8.1.10-fpm

MAINTAINER info@lukashron.cz

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    python wget gnupg g++ locales unzip dialog apt-utils curl git \
    && apt-get clean

RUN apt-get update && apt-get install -y libonig-dev libicu-dev libxml2-dev

RUN docker-php-ext-install mbstring intl opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:1000 /var/www && usermod -u 1000 www-data
USER www-data:1000
WORKDIR /var/www/html