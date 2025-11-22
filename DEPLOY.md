# Guía de Despliegue - Sistema de Gestión Contable

## Opciones de Despliegue

### 1. Railway.app

#### Paso 1: Preparar el Proyecto
```bash
# Asegúrate de tener todos los archivos Docker
git add Dockerfile .dockerignore docker/
git commit -m "Add Docker configuration"
git push origin main
```

#### Paso 2: Conectar con Railway
1. Ve a [railway.app](https://railway.app)
2. Inicia sesión con GitHub
3. Clic en "New Project"
4. Selecciona "Deploy from GitHub repo"
5. Elige tu repositorio

#### Paso 3: Configurar Variables de Entorno
En Railway, agrega estas variables:
```
APP_NAME=Sistema Contabilidad
APP_ENV=production
APP_DEBUG=false
APP_KEY=(se generará automáticamente)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

#### Paso 4: Configurar Build
Railway detectará automáticamente el Dockerfile. Si no:
- Build Command: (dejar vacío, Docker lo maneja)
- Start Command: (dejar vacío, Docker lo maneja)

#### Paso 5: Desplegar
Railway construirá y desplegará automáticamente.

---

### 2. Render.com

#### Paso 1: Preparar el Proyecto
```bash
git add Dockerfile .dockerignore docker/
git commit -m "Add Docker configuration"
git push origin main
```

#### Paso 2: Conectar con Render
1. Ve a [render.com](https://render.com)
2. Inicia sesión con GitHub
3. Clic en "New +" → "Web Service"
4. Conecta tu repositorio

#### Paso 3: Configurar Servicio
- **Name:** sistema-contabilidad
- **Environment:** Docker
- **Region:** Elige la más cercana
- **Branch:** main
- **Dockerfile Path:** Dockerfile (dejar por defecto)

#### Paso 4: Variables de Entorno
Agregar en "Environment":
```
APP_NAME=Sistema Contabilidad
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

#### Paso 5: Desplegar
Clic en "Create Web Service"

---

### 3. Heroku

#### Paso 1: Instalar Heroku CLI
```bash
# Descargar desde https://devcenter.heroku.com/articles/heroku-cli
```

#### Paso 2: Login
```bash
heroku login
```

#### Paso 3: Crear App
```bash
heroku create tu-app-contabilidad
```

#### Paso 4: Configurar Buildpack
```bash
heroku buildpacks:set heroku/php
```

#### Paso 5: Variables de Entorno
```bash
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set DB_CONNECTION=sqlite
```

#### Paso 6: Desplegar
```bash
git push heroku main
```

---

### 4. Desarrollo Local con Docker

#### Opción A: Docker Compose (Recomendado)

```bash
# Construir y levantar
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener
docker-compose down
```

#### Opción B: Docker Directo

```bash
# Construir imagen
docker build -t sistema-contabilidad .

# Ejecutar contenedor
docker run -d \
  --name contabilidad-app \
  -p 8000:8000 \
  -v $(pwd):/var/www/html \
  sistema-contabilidad

# Ver logs
docker logs -f contabilidad-app
```

---

## Comandos Útiles

### Verificar que Docker funciona
```bash
docker --version
docker-compose --version
```

### Construir imagen localmente
```bash
docker build -t sistema-contabilidad .
```

### Ejecutar contenedor de prueba
```bash
docker run -it --rm -p 8000:8000 sistema-contabilidad
```

### Acceder al contenedor
```bash
docker exec -it sistema-contabilidad bash
```

### Ver logs
```bash
docker logs sistema-contabilidad
```

---

## Solución de Problemas

### Error: "Cannot connect to database"
- Verifica que la base de datos SQLite tenga permisos de escritura
- Asegúrate de que el directorio `database/` existe

### Error: "APP_KEY not set"
- El script `start.sh` genera automáticamente la clave
- Si persiste, ejecuta manualmente: `php artisan key:generate`

### Error: "Permission denied"
- Verifica permisos en `storage/` y `bootstrap/cache/`
- El script `start.sh` configura permisos automáticamente

### Error: "Port already in use"
- Cambia el puerto en `docker-compose.yml` o `Dockerfile`
- O detén el proceso que usa el puerto 8000

---

## Notas Importantes

1. **SQLite en Producción:** SQLite funciona bien para aplicaciones pequeñas/medianas. Para producción de alto tráfico, considera migrar a PostgreSQL o MySQL.

2. **Archivos .env:** Nunca subas `.env` a Git. Railway/Render permiten configurar variables de entorno desde su panel.

3. **Base de Datos:** En producción, considera usar un servicio de base de datos gestionado (Railway Postgres, Render Postgres, etc.)

4. **Backups:** Implementa backups regulares de la base de datos SQLite.

5. **SSL/HTTPS:** Railway y Render proporcionan HTTPS automáticamente.

---

## Migración a PostgreSQL (Opcional)

Si necesitas migrar a PostgreSQL en producción:

1. Cambiar `DB_CONNECTION=sqlite` a `DB_CONNECTION=pgsql`
2. Agregar variables:
   ```
   DB_HOST=tu-host-postgres
   DB_PORT=5432
   DB_DATABASE=contabilidad
   DB_USERNAME=usuario
   DB_PASSWORD=password
   ```
3. Actualizar Dockerfile para incluir `pdo_pgsql`

---

**Última actualización:** 2024

