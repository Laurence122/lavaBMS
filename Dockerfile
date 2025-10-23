# Use official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite for LavaLust clean URLs
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy app files into container
COPY . /var/www/html

# Set runtime (logs/cache) permissions
RUN chown -R www-data:www-data /var/www/html/runtime \
    && chmod -R 775 /var/www/html/runtime

# Copy Apache config
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 8080

# Run Apache in foreground
CMD ["apache2-foreground"]
