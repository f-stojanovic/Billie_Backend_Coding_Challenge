FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    wget

# Installing Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer --version

RUN apt-get -y install cron default-mysql-client
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

WORKDIR /app
