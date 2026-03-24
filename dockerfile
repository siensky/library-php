# Vi använder en färdig PHP-bild med Apache
FROM php:8.2-apache

# Installera stöd för PostgreSQL (eftersom du använder pg_query)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Kopiera in alla dina filer till webbserverns mapp
COPY . /var/www/html/

# Säg åt Apache att lyssna på den port Render ger oss
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Starta Apache
CMD ["apache2-foreground"]