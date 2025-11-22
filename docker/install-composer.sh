#!/bin/bash
set -e

echo "📦 Instalando dependencias de Composer..."

# Intentar instalación normal primero
if composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader; then
    echo "✅ Composer install exitoso"
else
    echo "⚠️ Fallo en instalación normal, intentando con --ignore-platform-reqs..."
    composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --optimize-autoloader \
        --ignore-platform-reqs
fi

echo "✅ Dependencias instaladas"

