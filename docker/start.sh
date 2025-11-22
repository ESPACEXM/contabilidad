#!/bin/bash
# No usar set -e para permitir que los comandos fallen sin detener el script
set +e

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
APP_URL=https://contabilidad-1-o9f5.onrender.com
ASSET_URL=
# Forzar HTTPS en producción (Render usa HTTPS)
FORCE_HTTPS=true

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

# CRÍTICO: Crear directorios de cache ANTES de cualquier comando artisan
echo "📁 Creando directorios de cache..."
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# CRÍTICO: Eliminar cache de servicios ANTES de cualquier comando artisan
# El cache puede tener referencias a dependencias de desarrollo que no están instaladas
echo "🧹 Limpiando cache de servicios..."
rm -rf bootstrap/cache/services.php bootstrap/cache/packages.php bootstrap/cache/config.php bootstrap/cache/routes*.php || true

# Generar clave de aplicación si no existe (ANTES de otros comandos artisan)
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generando clave de aplicación..."
    # Usar script PHP directo para evitar cargar Laravel y sus dependencias
    php /var/www/html/docker/generate-key.php 2>/dev/null || \
    php -r "\$key='base64:'.base64_encode(random_bytes(32));\$f=file_get_contents('.env');file_put_contents('.env',preg_replace('/^APP_KEY=.*$/m',\"APP_KEY=\$key\",\$f)?:\$f.\"APP_KEY=\$key\n\");" || true
fi

# Limpiar caches de Laravel (después de tener APP_KEY)
# Redirigir stderr a /dev/null para evitar errores de Collision
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# Ejecutar migraciones
echo "🔄 Ejecutando migraciones..."
php artisan migrate --force 2>/dev/null || true

# Ejecutar seeders solo si la base de datos está vacía
echo "🌱 Ejecutando seeders..."
php artisan db:seed --force 2>/dev/null || true

# Optimizar para producción (ignorar errores de dependencias de desarrollo)
echo "⚡ Optimizando aplicación..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Asegurar permisos (por si acaso)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "✅ Aplicación lista!"
echo "🌐 Iniciando servidor en http://0.0.0.0:8000"

# Iniciar servidor PHP
exec php artisan serve --host=0.0.0.0 --port=8000

