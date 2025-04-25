FROM php:8.2-fpm-alpine

# Instalar dependencias y extensiones necesarias
RUN apk update && apk add --no-cache \
    git \
    unzip \
    curl \
    libpq-dev \
    zip \
    dos2unix \
    bash \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Copiar el script de inicialización y hacerlo ejecutable
COPY init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh \
    && dos2unix /usr/local/bin/init.sh

# Establecer el comando por defecto
CMD ["/usr/local/bin/init.sh"]
