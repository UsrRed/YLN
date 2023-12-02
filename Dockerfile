FROM php:8.2-fpm

RUN apt-get update

RUN docker-php-ext-install mysqli

RUN chmod 666 /home/logs/logs.txt
