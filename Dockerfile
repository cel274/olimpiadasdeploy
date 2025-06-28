FROM php:8.2-apache

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    unzip \
    zip \
    && docker-php-ext-install zip gd

# Habilita mod_rewrite si usás URLs limpias
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos al contenedor
COPY . /var/www/html/

# Establece el directorio de trabajo
WORKDIR /var/www/html/

# Instala dependencias de Composer si hay
RUN composer install --no-interaction --prefer-dist

# Exponer el puerto por si hace falta
EXPOSE 80
