FROM php:8.2-apache

# Copy your PHP file to the web root
COPY index.php /var/www/html/

# Copy image files
COPY *.jpeg /var/www/html/
COPY *.jpg /var/www/html/

# Expose port 80
EXPOSE 80