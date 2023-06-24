FROM php:7.4-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip

RUN docker-php-ext-install pdo_mysql zip

# Set the working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock /var/www/html/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the app's code
COPY . /var/www/html

# Set up permissions
RUN chown -R www-data:www-data /var/www/html/var

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
