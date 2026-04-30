FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql zip

RUN sed -i 's/127.0.0.1:9000/9000/g' /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]