# Use the official PHP image for Symfony
FROM php:8.0-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip

# Enable necessary PHP extensions for Symfony
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application files to the container
COPY . .

# Change the ownership of the application files to the web server user
RUN chown -R www-data:www-data /var/www/html

# Enable Apache mod_rewrite for Symfony
RUN a2enmod rewrite

# Set the document root to the public directory of the Symfony application
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Start the Apache server
CMD ["apache2-foreground"]
