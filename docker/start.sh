#!/bin/bash
set -e

echo "🚀 Iniciando Sistema de Gestión Contable..."

# Esperar a que la base de datos esté lista (si es necesario)
# En este caso con SQLite no es necesario, pero se deja para futuras migraciones

# Crear base de datos SQLite si no existe
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "📦 Creando base de datos SQLite..."
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Verificar si .env existe, si no, crear uno básico
if [ ! -f /var/www/html/.env ]; then
    echo "⚙️ Creando archivo .env..."
    cat > /var/www/html/.env << EOF
APP_NAME="Sistema Contabilidad"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF
fi

# Ejecutar scripts de Composer que se saltaron durante el build
echo "📦 Ejecutando scripts de Composer..."
composer run-script post-autoload-dump || true

# Generar clave de aplicación si no existe
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generando clave de aplicación..."
    php artisan key:generate --force
fi

# Ejecutar migraciones
echo "🔄 Ejecutando migraciones..."
php artisan migrate --force || true

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Asegurar permisos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Aplicación lista!"
echo "🌐 Iniciando servidor en http://0.0.0.0:8000"

# Iniciar servidor PHP
exec php artisan serve --host=0.0.0.0 --port=8000

