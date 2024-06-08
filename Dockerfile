# Stage 1: Build the application
FROM composer:latest as build

WORKDIR /app
COPY ./ /app
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

# Stage 2: Set up the production environment with Apache, PHP, and MySQL
FROM php:8.2-apache as production

# Install necessary PHP extensions and MySQL server
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev mysql-server && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Set up MySQL
RUN service mysql start && \
    mysql -e "CREATE DATABASE laravel;" && \
    mysql -e "CREATE USER 'sail'@'localhost' IDENTIFIED BY 'password';" && \
    mysql -e "GRANT ALL PRIVILEGES ON laravel.* TO 'sail'@'localhost';" && \
    mysql -e "FLUSH PRIVILEGES;"

# Copy application code and configuration
COPY --from=build /app /var/www/html
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .env.prod /var/www/html/.env

# Update the Apache configuration to listen on port 3000
RUN sed -i 's/80/3000/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:3000/' /etc/apache2/sites-available/000-default.conf

# Enable Apache rewrite module and restart Apache service
RUN a2enmod rewrite && \
    service apache2 restart

# Expose port 3000
EXPOSE 3000

# Set entrypoint to start MySQL, run migrations, and start Apache
CMD service mysql start && \
    sleep 10 && \
    php artisan migrate --seed && \
    php artisan config:clear && \
    php artisan route:clear && \
    chmod 777 -R /var/www/html/storage/ && \
    apache2-foreground
