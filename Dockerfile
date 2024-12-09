FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
