FROM php:8.3-fpm-bullseye

ARG PHALCON_VERSION

RUN pecl channel-update pecl.php.net

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN pecl install phalcon-${PHALCON_VERSION}
RUN docker-php-ext-enable phalcon && php -m

RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get update \
    && apt-get install -y cron rsyslog vim zip

COPY composer-setup.sh ./
RUN chmod +x ./composer-setup.sh && ./composer-setup.sh && mv composer.phar /usr/local/bin/composer

# This is for DEV docker image, change for PROD!
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
ENV ENVIRONMENT=DEV
