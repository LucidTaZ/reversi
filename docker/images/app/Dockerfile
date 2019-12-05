FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y \
    # Needed for composer:
    git unzip

RUN curl --silent --show-error https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /reversi

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown www-data /reversi
USER www-data
