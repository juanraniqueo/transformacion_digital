# Imagen base con PHP 8.2 + extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Generar APP_KEY si no existe (útil para contenedor)
RUN php artisan key:generate --force

# Exponer el puerto 8000
EXPOSE 8000

# Comando por defecto (servidor de Laravel)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
