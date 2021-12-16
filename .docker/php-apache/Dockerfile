FROM php:8.0.11-apache

# Копирование фалов проекта
COPY ./../../ /var/www/html

# Замена дефолтного хоста Apache
COPY ./../../.docker/php-apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Обновление пакетов
RUN apt-get update

# Установка и активация расширения pdo_mysql
RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite