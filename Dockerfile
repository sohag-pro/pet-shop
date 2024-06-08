FROM composer:latest as build

WORKDIR /app
COPY ./ /app
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8.2-apache as production

RUN docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install pdo pdo_mysql

COPY --from=build /app /var/www/html
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .env.prod /var/www/html/.env

# Update the Apache configuration to listen on port 3000
RUN sed -i 's/80/3000/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:3000/' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite 
RUN service apache2 restart 
RUN php artisan migrate --seed
RUN php artisan config:clear && php artisan route:clear && chmod 777 -R /var/www/html/storage/

# Expose port 3000
EXPOSE 3000

CMD ["apache2-foreground"]
