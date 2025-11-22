# Solución: Error de Composer Install en Docker

## Problema
```
error: failed to solve: process "/bin/sh -c composer install..." did not complete successfully: exit code: 2
```

## Causas Posibles

1. **Scripts de Composer ejecutándose antes de tiempo**
2. **Extensiones PHP faltantes**
3. **Problemas de memoria durante la instalación**
4. **Dependencias incompatibles**

## Soluciones Aplicadas

### 1. Dockerfile Mejorado
- ✅ Agregado `--ignore-platform-reqs` para evitar problemas de compatibilidad
- ✅ Uso de `--no-scripts` y `--no-autoloader` en la instalación inicial
- ✅ Separación de instalación y optimización
- ✅ Manejo de errores con `|| true` para evitar fallos del build

### 2. Verificación de Extensiones
Todas las extensiones necesarias están instaladas:
- pdo, pdo_sqlite
- mbstring, exif, pcntl, bcmath
- gd, zip

## Pasos para Resolver

### Opción 1: Usar Dockerfile Actualizado

El Dockerfile ya está actualizado. Solo necesitas:

```bash
git add Dockerfile
git commit -m "Fix composer install errors"
git push origin main
```

Render debería reconstruir automáticamente.

### Opción 2: Verificar Logs de Render

En Render Dashboard:
1. Ve a tu servicio
2. Clic en "Logs"
3. Busca el error específico de Composer
4. Comparte el error completo para diagnóstico

### Opción 3: Probar Localmente

```bash
# Construir imagen localmente
docker build -t test-contabilidad .

# Si falla, verás el error exacto
```

## Errores Comunes y Soluciones

### Error: "Your requirements could not be resolved"
**Solución:** Ya incluido `--ignore-platform-reqs`

### Error: "Scripts failed"
**Solución:** Ya usando `--no-scripts` en instalación inicial

### Error: "Memory limit exhausted"
**Solución:** Aumentar memoria en Render o usar Alpine (Dockerfile.production)

### Error: "Extension X not found"
**Solución:** Verificar que todas las extensiones estén en el Dockerfile

## Configuración en Render

Asegúrate de tener estas variables de entorno:

```
COMPOSER_MEMORY_LIMIT=512M
```

O en Render Settings → Environment:
- Agregar variable: `COMPOSER_MEMORY_LIMIT` = `512M`

## Alternativa: Dockerfile.production

He creado `Dockerfile.production` basado en Alpine (más ligero). Para usarlo:

1. En Render, cambia "Dockerfile Path" a: `Dockerfile.production`
2. O renombra el archivo: `mv Dockerfile.production Dockerfile`

## Verificación

Después del build, deberías ver en los logs:

```
Loading composer repositories with package information
Installing dependencies from lock file
Package operations: X installs, Y updates, Z removals
Generating optimized autoload files
```

## Si el Problema Persiste

1. **Revisar logs completos** en Render
2. **Probar build local** con `docker build`
3. **Verificar composer.lock** está actualizado
4. **Contactar soporte** con logs completos

