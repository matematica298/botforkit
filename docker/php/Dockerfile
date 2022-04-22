FROM php:7.4.8-fpm-alpine

RUN docker-php-ext-install pdo_mysql bcmath
RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo
