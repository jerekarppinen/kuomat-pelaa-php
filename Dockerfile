FROM php:8.2-apache

# Update system packages to reduce vulnerabilities
RUN apt-get update && apt-get upgrade -y && apt-get clean

# Copy your PHP file to the web root
COPY index.php /var/www/html/

# Copy image files
COPY *.jpeg /var/www/html/
COPY *.jpg /var/www/html/
COPY *.css /var/www/html/
COPY *.ico /var/www/html/

# Expose port 80
EXPOSE 80