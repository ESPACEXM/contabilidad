# Dockerfile para Sistema de Gestión Contable Laravel
FROM php:8.3-fpm

# Instalar dependencias del sistema (incluyendo Node.js para compilar assets)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nginx \
    supervisor \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && docker-php-ext-enable pdo pdo_sqlite mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Verificar que Composer está instalado
RUN composer --version

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de configuración de Composer primero (para cache)
COPY composer.json composer.lock* ./

# Instalar dependencias de Composer (solo producción)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --ignore-platform-reqs

# Copiar archivos necesarios para compilar assets PRIMERO
COPY package.json package-lock.json* ./
COPY vite.config.js tailwind.config.js postcss.config.js* ./
COPY resources ./resources
# Crear directorio public para que Vite pueda escribir ahí
RUN mkdir -p ./public

# Instalar dependencias de Node.js (incluyendo dev para compilar)
# Luego compilar assets ANTES de copiar el resto de archivos
RUN echo "📦 Instalando dependencias npm..." && \
    npm ci --no-audit --no-fund || npm install --no-audit --no-fund || true

RUN echo "🔨 Compilando assets con Vite..." && \
    npm run build && \
    echo "✅ Assets compilados exitosamente" && \
    ls -la public/build/ || true

# Verificar que los assets se compilaron
RUN test -f public/build/manifest.json && echo "✅ manifest.json encontrado" || (echo "❌ ERROR: manifest.json NO encontrado" && exit 1)

# Limpiar node_modules para reducir tamaño
RUN rm -rf node_modules || true

# Copiar el resto de los archivos del proyecto (public/build ya está compilado)
COPY . .

# Limpiar cache de servicios que puede tener referencias a dependencias de desarrollo
RUN rm -f bootstrap/cache/services.php bootstrap/cache/packages.php || true

# Ejecutar scripts de Composer y optimizar autoloader
# Usar --no-scripts para evitar ejecutar artisan durante el build
RUN composer dump-autoload \
    --optimize \
    --classmap-authoritative \
    --no-dev \
    --no-scripts

# Crear directorios necesarios y configurar permisos
RUN mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/database

# Copiar configuración de PHP
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Exponer puerto
EXPOSE 8000

# Script de inicio y utilidades
COPY docker/start.sh /usr/local/bin/start.sh
COPY docker/generate-key.php /var/www/html/docker/generate-key.php
RUN chmod +x /usr/local/bin/start.sh

# Comando para iniciar la aplicación
CMD ["/usr/local/bin/start.sh"]

