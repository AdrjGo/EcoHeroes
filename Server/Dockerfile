# Etapa de construcción
FROM php:8.2-fpm AS builder

# Instalar dependencias con versión específica para compatibilidad
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

# Configuración de OPcache para producción
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar solo lo necesario para instalar dependencias (optimiza caché Docker)
COPY composer.json composer.lock ./

# Instalar dependencias sin dev (para producción)
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Copiar todo el código
COPY . .

# Generar clave de aplicación y optimizar (CORREGIDO)
RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi

# ----------------------------------------
# Etapa de producción
FROM php:8.2-fpm

# Instalar solo lo esencial para producción
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd opcache

# Configuración de PHP para producción
COPY --from=builder /usr/local/etc/php/conf.d/opcache-recommended.ini /usr/local/etc/php/conf.d/
COPY --from=builder /var/www /var/www

WORKDIR /var/www

# Configurar permisos para Laravel
RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache

# Health check para el contenedor
HEALTHCHECK --interval=30s --timeout=3s \
    CMD curl -f http://localhost:8000 || exit 1

EXPOSE 9000

CMD ["php-fpm"]
