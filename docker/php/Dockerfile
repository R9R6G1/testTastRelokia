FROM composer:latest as setup

COPY . .

RUN composer update  \
&& composer clear-cache

WORKDIR /guzzle

RUN set -xe \
    && composer init --name=guzzlehttp/test --description="Simple project for testing Guzzle scripts" --author="Márk Sági-Kazár <mark.sagikazar@gmail.com>" --no-interaction \
    && composer require guzzlehttp/guzzle

FROM php:8-cli

COPY --from=setup /guzzle /guzzle

RUN apt-get update && apt-get install -y curl git zip \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo mysqli pdo_mysql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer