FROM php:8.2-fpm

RUN apt-get update

RUN apt-get update && echo "yes" | DEBIAN_FRONTEND=noninteractive apt-get install -y msmtp

RUN docker-php-ext-install mysqli

