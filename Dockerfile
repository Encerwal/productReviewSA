# Use an official PHP image with Apache
FROM php:7.4-apache

# Install PostgreSQL PDO extension along with any other extensions
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Copy your project files to the Apache document root
COPY . /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80

# Start the Apache server in the foreground (keeps container running)
CMD ["apache2-foreground"]
