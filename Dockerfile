FROM composer:latest

ADD . /app

RUN php --version

WORKDIR /app

RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist