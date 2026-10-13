#!/bin/bash

# Terminar el script si algún comando falla
set -e

# 1. Copiar .env si no existe
if [ ! -f .env ]; then
    echo "Fichero .env no encontrado. Creando a partir de .env.example..."
    cp .env.example .env
fi

# 2. Instalar dependencias de Composer si falta la carpeta vendor
if [ ! -d "vendor" ]; then
    echo "Carpeta vendor no encontrada. Ejecutando composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Generar la clave de la aplicación si no está definida
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generando APP_KEY..."
    php artisan key:generate
fi

# 4. Pasar el control al comando principal del contenedor (ej. php-fpm o apache)
exec "$@"