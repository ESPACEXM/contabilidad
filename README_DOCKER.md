# 🐳 Guía de Docker - Sistema de Gestión Contable

## Problemas Comunes y Soluciones

### Error: "composer install did not complete successfully"

Este error puede tener varias causas. He aplicado las siguientes correcciones:

#### ✅ Soluciones Aplicadas:

1. **Agregado `--ignore-platform-reqs`**
   - Ignora requisitos de plataforma que pueden causar problemas

2. **Uso de `--no-scripts` y `--no-autoloader`**
   - Evita ejecutar scripts que requieren archivos aún no copiados

3. **Separación de instalación y optimización**
   - Primero instala dependencias, luego optimiza

4. **Verificación de Composer**
   - Se verifica que Composer esté instalado correctamente

## Si el Error Persiste

### 1. Verificar Logs Completos en Render

En Render Dashboard → Logs, busca el error específico. Puede ser:

- **"Memory limit exhausted"** → Agregar variable `COMPOSER_MEMORY_LIMIT=512M`
- **"Extension X not found"** → Verificar Dockerfile tiene todas las extensiones
- **"Script failed"** → Ya manejado con `--no-scripts`

### 2. Probar Build Local

```bash
# Construir localmente para ver el error exacto
docker build -t test-contabilidad .

# Ver logs detallados
docker build --progress=plain -t test-contabilidad .
```

### 3. Usar Dockerfile.production (Alpine)

Si el problema persiste, prueba con la versión Alpine (más ligera):

```bash
# En Render, cambiar Dockerfile Path a:
Dockerfile.production
```

### 4. Agregar Variable de Memoria en Render

En Render Settings → Environment, agregar:

```
COMPOSER_MEMORY_LIMIT=512M
```

## Comandos Útiles

### Verificar Dockerfile
```bash
docker build --no-cache -t test .
```

### Probar contenedor
```bash
docker run -it --rm -p 8000:8000 test
```

### Ver logs de build
```bash
docker build --progress=plain . 2>&1 | tee build.log
```

## Estado Actual del Dockerfile

✅ PHP 8.2-FPM  
✅ Todas las extensiones necesarias  
✅ Composer instalado  
✅ SQLite configurado  
✅ Scripts de inicio automáticos  
✅ Manejo de errores mejorado  

## Próximos Pasos

1. **Subir cambios a GitHub:**
```bash
git add Dockerfile docker/ SOLUCION_COMPOSER_ERROR.md
git commit -m "Fix composer install errors in Dockerfile"
git push origin main
```

2. **Render reconstruirá automáticamente**

3. **Si falla, revisar logs en Render y compartir el error específico**

