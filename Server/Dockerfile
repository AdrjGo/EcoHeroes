FROM php:8.2-fpm

# Instalar dependencias y la extensión pdo_pgsql
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev zip dos2unix \
    && docker-php-ext-install pdo pdo_pgsql
# Instalar Composer (se copia desde la imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www
