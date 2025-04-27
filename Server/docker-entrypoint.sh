# #!/bin/sh
#
# set -e
#
# # Esperar a que PostgreSQL esté lista
# while ! nc -z db 5432; do
#     echo "Esperando a PostgreSQL..."
#     sleep 1
# done
#
# # Generar APP_KEY si no existe
# if [ -z "$(grep 'APP_KEY=' .env)" ]; then
#     echo "Generando APP_KEY..."
#     php artisan key:generate --force
# fi
#
# # Ejecutar migraciones
# php artisan migrate --force
#
# # Iniciar el servidor
# exec php-fpm
