FROM php:7.2-apache

ENV MYSQL_DATABASE=test \
    MYSQ_ROOT_PASSWORD=root

RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev default-mysql-client && docker-php-ext-install pdo_mysql mysqli gd iconv && docker-php-ext-install mbstring

COPY test.zz.ua.conf /etc/apache/sites-available/test.zz.ua.conf
COPY hosts /etc/hosts
RUN a2enmod rewrite

#RUN a2enmod mcrypt
RUN service apache2 restart
WORKDIR /etc/apache2/sites-available/
RUN a2ensite test.zz.ua

EXPOSE 80