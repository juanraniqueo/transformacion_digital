# Imagen base oficial de PHP con extensiones mínimas
FROM php:8.2-cli

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos de composer e instalar dependencias (SIN scripts)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-progress --prefer-dist --no-dev --no-scripts

# Copiar el resto del código de la aplicación
COPY . .

# Dar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer el puerto 8000
EXPOSE 8000

# Comando por defecto: levantar el servidor embebido de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
