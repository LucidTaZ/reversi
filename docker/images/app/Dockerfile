FROM alpine:edge
# edge because the current latest (3.7) doesn't have a composer package

RUN apk add --update php7-fpm \
    php7-session php7-ctype php7-iconv php7-mbstring \
    composer unzip \
    && rm -rf /var/cache/apk/*

WORKDIR /reversi

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer install --no-dev --optimize-autoloader

# php-fpm config
RUN echo "" >> /etc/php7/php-fpm.d/www.conf \
    && echo "; Dockerfile-managed settings:" >> /etc/php7/php-fpm.d/www.conf \
    && echo "[global]" >> /etc/php7/php-fpm.d/www.conf \
    && echo "error_log = /proc/self/fd/2" >> /etc/php7/php-fpm.d/www.conf \
    && echo "[www]" >> /etc/php7/php-fpm.d/www.conf \
    && echo "access.log = /proc/self/fd/2" >> /etc/php7/php-fpm.d/www.conf \
    && echo "listen = 9000" >> /etc/php7/php-fpm.d/www.conf \
    && echo "catch_workers_output = yes" >> /etc/php7/php-fpm.d/www.conf \
    && echo "clear_env = no" >> /etc/php7/php-fpm.d/www.conf

EXPOSE 9000

CMD ["php-fpm7", "--nodaemonize"]
