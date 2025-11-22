# Diagnóstico: Assets No Cargando

## 🔍 Pasos para Diagnosticar

### 1. Verificar en el Navegador (DevTools)

Abre las **Herramientas de Desarrollador** (F12) y:

1. **Pestaña Network:**
   - Recarga la página
   - Busca `app-*.css` y `app-*.js`
   - Verifica el **Status Code**:
     - ✅ **200 OK** = Archivo cargado correctamente
     - ❌ **404 Not Found** = Archivo no existe
     - ❌ **403 Forbidden** = Problema de permisos

2. **Pestaña Console:**
   - Busca errores relacionados con:
     - `Failed to load resource`
     - `404`
     - `CORS`

3. **Inspeccionar el HTML:**
   - Busca en el `<head>` los tags:
     ```html
     <link rel="stylesheet" href="...">
     <script type="module" src="...">
     ```
   - Verifica que las URLs sean correctas

### 2. Verificar en Render

1. **Logs del Build:**
   - Busca: `✅ manifest.json encontrado`
   - Busca: `✅ Assets compilados exitosamente`

2. **Logs de Runtime:**
   - Busca errores de PHP
   - Busca errores relacionados con `public_path`

### 3. Verificar Variables de Entorno en Render

Asegúrate de que `APP_URL` esté configurado:
```
APP_URL=https://tu-app.onrender.com
```

### 4. Verificar Archivos en el Contenedor

Si tienes acceso SSH:
```bash
ls -la /var/www/html/public/build/
cat /var/www/html/public/build/manifest.json
```

## 🛠️ Soluciones Comunes

### Problema 1: Archivos dan 404

**Causa:** Los archivos no se copiaron al contenedor o están en la ruta incorrecta.

**Solución:**
1. Verifica que `public/build/` esté en el repositorio
2. Verifica que `.gitignore` no excluya `public/build`
3. Verifica que el Dockerfile copie `public/build/`

### Problema 2: URLs Incorrectas

**Causa:** `APP_URL` no está configurado o es incorrecto.

**Solución:**
1. Configura `APP_URL` en Render:
   ```
   APP_URL=https://tu-app.onrender.com
   ```
2. Limpia cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Problema 3: Permisos Incorrectos

**Causa:** Los archivos no tienen permisos de lectura.

**Solución:**
En el Dockerfile, asegúrate de:
```dockerfile
RUN chmod -R 755 /var/www/html/public
```

### Problema 4: Manifest No Encontrado

**Causa:** El manifest no se compiló o no se copió.

**Solución:**
1. Verifica que `npm run build` se ejecutó
2. Verifica que `public/build/manifest.json` existe
3. Si no existe, compila localmente y súbelo

## 📝 Comandos de Verificación

```bash
# Verificar que los assets existen localmente
ls -la public/build/assets/

# Verificar el manifest
cat public/build/manifest.json

# Verificar que están en git
git ls-files public/build/

# Limpiar cache
php artisan view:clear
php artisan config:clear
```

## 🚀 Solución Rápida

Si nada funciona, compila y sube los assets manualmente:

```bash
# 1. Compilar
npm run build

# 2. Verificar
ls -la public/build/

# 3. Asegurar que .gitignore no excluya public/build
# (comentar la línea: # /public/build)

# 4. Subir
git add public/build
git commit -m "Force add compiled assets"
git push
```

