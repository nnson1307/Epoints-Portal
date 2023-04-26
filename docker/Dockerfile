FROM php:7.4-apache

WORKDIR /var/www/html

COPY apache/apache-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /var/run/apache2/

RUN a2enmod headers rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update -y && apt-get install -y libmariadb-dev curl sudo git zip unzip libcap2-bin libpng-dev libpng-dev libzip-dev vim \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-install mysqli pdo pdo_mysql pcntl zip gd

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
