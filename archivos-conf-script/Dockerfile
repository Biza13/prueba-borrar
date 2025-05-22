FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y git unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el código de la app al contenedor
WORKDIR /var/www/html/symfony
COPY ./symfony/ .

# Instala las dependencias de Composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Da permisos a las carpetas
RUN chown -R www-data:www-data var vendor && chmod -R 775 var vendor
