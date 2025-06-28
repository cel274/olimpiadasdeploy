FROM php:8.2-apache

# Instalar dependencias del sistema necesarias para GD, ZIP y Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Habilitar mod_rewrite para Apache si usás URLs limpias
RUN a2enmod rewrite

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todos los archivos del proyecto
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html/

# Instalar dependencias PHP del proyecto
RUN composer install --no-interaction --prefer-dist || true

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
