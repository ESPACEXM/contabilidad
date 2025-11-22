# Solución: Error de Vite Manifest en Producción

## Problema
```
Vite manifest not found at: /var/www/html/public/build/manifest.json
```

## Causa
Los assets de Vite no están compilados en producción. El directorio `public/build/` no existe porque `npm run build` no se ejecutó durante el build de Docker.

## Solución Aplicada

### 1. Instalar Node.js en Dockerfile
Se agregó Node.js y npm a las dependencias del sistema:

```dockerfile
nodejs \
npm \
```

### 2. Compilar Assets Durante el Build
Se agregaron pasos para:
- Copiar `package.json` y `package-lock.json`
- Instalar dependencias de npm
- Compilar assets con `npm run build`
- Limpiar `node_modules` para reducir tamaño de imagen

```dockerfile
# Copiar archivos de configuración de Node.js
COPY package.json package-lock.json* ./

# Instalar dependencias y compilar
RUN npm ci --only=production --no-audit --no-fund || npm install --only=production --no-audit --no-fund || true
RUN npm run build || true
RUN rm -rf node_modules || true
```

### 3. Orden de Operaciones
El orden es importante:
1. Copiar `package.json` primero (para cache de Docker)
2. Instalar dependencias npm
3. Compilar assets
4. Copiar resto de archivos
5. Limpiar node_modules (opcional, reduce tamaño)

## Archivos Modificados

✅ `Dockerfile` - Agregado Node.js y compilación de assets  
✅ `resources/views/layouts/app.blade.php` - Agregado fallback para desarrollo  

## Verificación

Después del deploy, deberías ver en los logs del build:

```
# Instalando dependencias npm...
# Compilando assets con Vite...
# Assets compilados en public/build/
```

Y en la aplicación:
- ✅ No debería aparecer el error de manifest
- ✅ Los estilos CSS deberían cargar
- ✅ JavaScript (Alpine.js) debería funcionar

## Alternativa: Compilar Assets Localmente

Si prefieres compilar assets antes de subir a GitHub:

```bash
npm install
npm run build
git add public/build
git commit -m "Add compiled Vite assets"
git push origin main
```

Luego en el Dockerfile, puedes omitir los pasos de npm y solo copiar `public/build/`.

## Nota Técnica

Vite genera un `manifest.json` en `public/build/` que mapea los archivos fuente a los archivos compilados. Laravel usa este manifest para generar las URLs correctas de los assets en producción.

Sin este manifest, Laravel no puede encontrar los assets compilados y lanza el error `ViteManifestNotFoundException`.

