#!/bin/bash

set -e

echo "🚀 Iniciando script de inicialización..."

# Definir paths (usamos rutas absolutas dentro del contenedor)
ENV_EXAMPLE="/var/www/.env.example"
ENV_DOCKER="/var/www/.env.docker"
ENV_FILE="/var/www/.env"

# Si no existe .env, lo generamos fusionando .env.example y .env.docker
if [ ! -f "$ENV_FILE" ]; then
    echo "🔧 Generando archivo .env a partir de .env.example y .env.docker..."

    cp "$ENV_EXAMPLE" "$ENV_FILE"

    while IFS='=' read -r raw_key raw_value; do
        # Limpieza básica de claves y valores
        key=$(echo "$raw_key" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')
        value=$(echo "$raw_value" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')

        # Ignorar líneas vacías o comentarios
        if [[ -z "$key" || "$key" == \#* ]]; then
            continue
        fi

        # Si la clave ya existe, reemplazarla; si no, agregarla
        if grep -q "^$key=" "$ENV_FILE"; then
            sed -i "s|^$key=.*|$key=$value|" "$ENV_FILE"
        else
            echo "$key=$value" >>"$ENV_FILE"
        fi
    done < <(grep -v '^\s*$' "$ENV_DOCKER")

    echo "✅ Archivo .env generado correctamente."
else
    echo "ℹ️ Archivo .env ya existe, no se hará nada."
fi

# Limpiar caché de composer
echo "🧹 Limpiando caché de Composer..."
composer clear-cache

# Instalar dependencias base
echo "📦 Instalando dependencias base..."
rm -f composer.lock
composer install --no-interaction --prefer-dist

# Instalar Cloudinary
echo "📦 Instalando Cloudinary..."
composer require cloudinary/cloudinary_php --update-with-dependencies --no-interaction

echo "🔑 Generando clave de aplicación..."
php artisan key:generate 

echo "🧱 Ejecutando migraciones..."
php artisan migrate:fresh --seed --force

echo "🌐 Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
