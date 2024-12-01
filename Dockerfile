# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy the custom Apache configuration file
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Reload Apache to apply the configuration
RUN service apache2 reload

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy project files to the container
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
