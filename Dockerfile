FROM php:8.3-fpm-alpine3.20

ARG PHALCON_VERSION

RUN apk update \
    && apk add bash build-base autoconf curl gcc \
    && pecl channel-update pecl.php.net

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN pecl install phalcon-${PHALCON_VERSION}
RUN docker-php-ext-enable phalcon && php -m

RUN pecl install redis && docker-php-ext-enable redis

COPY composer-setup.sh ./
RUN chmod +x ./composer-setup.sh && ./composer-setup.sh && mv composer.phar /usr/local/bin/composer

# This is for DEV docker image, change for PROD!
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
ENV ENVIRONMENT=DEV
