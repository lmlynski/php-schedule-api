FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y \
        sudo \
        git \
        wget \
        tar \
        bzip2 \
        unzip \
        zip \
        libzip-dev \
        libcurl4-openssl-dev \
        libonig-dev \
        libxml2-dev \
        libmcrypt-dev \
        nano \
    && docker-php-ext-install pdo pdo_mysql mysqli opcache curl zip bcmath \
    && apt-get clean

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

WORKDIR /var/www/app
