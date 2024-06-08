FROM php:7.2-apache
RUN apt-get update -y && \
    apt-get upgrade -y && \
    docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli