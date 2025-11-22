# Solución al Error de Render: "Dokerfile"

## Problema
Render está buscando un archivo llamado "Dokerfile" (con "o") en lugar de "Dockerfile" (con "a").

## Solución

### Opción 1: Corregir en el Panel de Render (Recomendado)

1. **Ve a tu servicio en Render**
2. **Busca la sección "Settings" o "Configuración"**
3. **Encuentra el campo "Dockerfile Path" o "Docker File Path"**
4. **Verifica que diga exactamente:** `Dockerfile` (con "a", no "o")
5. **Si dice "Dokerfile", cámbialo a "Dockerfile"**
6. **O simplemente déjalo vacío** - Render usará "Dockerfile por defecto
7. **Guarda los cambios**
8. **Haz un nuevo deploy**

### Opción 2: Usar render.yaml (Automático)

He creado el archivo `render.yaml` que especifica correctamente el Dockerfile.

**Pasos:**
1. Asegúrate de que `render.yaml` esté en tu repositorio
2. En Render, cuando crees el servicio:
   - Selecciona "Apply Render YAML"
   - O simplemente Render lo detectará automáticamente

### Opción 3: Verificar en GitHub

Asegúrate de que el archivo `Dockerfile` esté en la raíz del repositorio:

```bash
# Verificar que existe
ls -la Dockerfile

# Si no está, agregarlo
git add Dockerfile
git commit -m "Add Dockerfile"
git push origin main
```

## Verificación Rápida

### En Render Dashboard:
1. Ve a tu servicio
2. Clic en "Settings"
3. Busca "Dockerfile Path"
4. Debe decir: `Dockerfile` o estar vacío
5. **NO debe decir:** `Dokerfile`

### En tu Repositorio:
```bash
# Verificar que el archivo existe
git ls-files | grep -i dockerfile

# Debe mostrar: Dockerfile
```

## Si el Problema Persiste

### 1. Eliminar y Recrear el Servicio
- Elimina el servicio actual en Render
- Crea uno nuevo
- Esta vez asegúrate de que el campo Dockerfile Path esté correcto

### 2. Verificar el Nombre del Archivo en Git
```bash
# Ver todos los archivos Dockerfile
git ls-files | grep -i docker

# Debe mostrar solo: Dockerfile
```

### 3. Forzar Push
```bash
git add Dockerfile
git commit -m "Fix Dockerfile name"
git push origin main --force
```

## Configuración Correcta en Render

**Campos Importantes:**
- **Environment:** Docker
- **Dockerfile Path:** `Dockerfile` (o vacío)
- **Docker Context:** `.` (punto, raíz del proyecto)
- **Build Command:** (vacío - Docker lo maneja)
- **Start Command:** (vacío - Docker lo maneja)

## Variables de Entorno Necesarias

Asegúrate de tener estas variables en Render:

```
APP_NAME=Sistema Contabilidad
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

**Nota:** `APP_KEY` se generará automáticamente por el script `start.sh`

## Después de Corregir

1. Guarda los cambios en Render
2. Render iniciará un nuevo build automáticamente
3. Deberías ver en los logs:
   ```
   #1 [internal] load build definition from Dockerfile
   #1 transferring dockerfile: XB done
   #1 DONE X.Xs
   ```

## Contacto con Soporte

Si nada funciona, contacta a Render support y menciona:
- El error exacto: "failed to read dockerfile: open Dokerfile"
- Que el archivo correcto es "Dockerfile" (con "a")
- Que necesitas ayuda para corregir la configuración

