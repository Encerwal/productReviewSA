# Use an official PHP image with Apache
FROM php:7.4-apache

# Install additional PHP extensions if needed (example: MySQL)
RUN docker-php-ext-install mysqli

# Copy your project files to the Apache document root
COPY . /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80

# Start the Apache server in the foreground (keeps container running)
CMD ["apache2-foreground"]
