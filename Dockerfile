FROM php:8.0-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y ffmpeg