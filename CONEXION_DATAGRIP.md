# Conexión a la Base de Datos desde DataGrip

## Información de la Base de Datos

- **Tipo**: SQLite
- **Ruta del archivo**: `C:\laragon\www\Proyecto Contabilidad\database\database.sqlite`

## Pasos para Conectar en DataGrip

### Opción 1: Crear Nueva Conexión SQLite

1. **Abrir DataGrip**

2. **Crear Nueva Conexión**:
   - Haz clic en el botón **"+"** (Add Data Source) en la barra de herramientas
   - Selecciona **"SQLite"**

3. **Configurar la Conexión**:
   - **File**: Haz clic en el botón de carpeta (📁) y navega a:
     ```
     C:\laragon\www\Proyecto Contabilidad\database\database.sqlite
     ```
   - O puedes escribir directamente la ruta:
     ```
     C:\laragon\www\Proyecto Contabilidad\database\database.sqlite
     ```

4. **Configuraciones Adicionales** (opcional):
   - **Name**: Puedes cambiar el nombre de la conexión (ej: "Proyecto Contabilidad SQLite")
   - **Test Connection**: Haz clic en "Test Connection" para verificar que la conexión funciona
   - Si aparece un error, asegúrate de que el archivo `database.sqlite` existe y tienes permisos de lectura

5. **Aplicar y Conectar**:
   - Haz clic en **"OK"** o **"Apply"**
   - La conexión aparecerá en el panel izquierdo de DataGrip

### Opción 2: Configuración Avanzada

Si necesitas configuraciones adicionales:

1. En la ventana de configuración de la conexión:
   - **User**: No se requiere para SQLite
   - **Password**: No se requiere para SQLite
   - **Database**: Dejar vacío (se usa el archivo directamente)

2. **Opciones** (opcional):
   - Activar **"Read-only"** si solo quieres ver los datos
   - Desactivar **"Auto-commit"** si quieres controlar las transacciones manualmente

### Verificación

Una vez conectado, deberías poder ver:
- Todas las tablas del proyecto en el panel izquierdo
- Tablas como: `users`, `tenants`, `chart_accounts`, `journal_entries`, `products`, etc.
- Puedes hacer consultas SQL directamente desde DataGrip

### Notas Importantes

⚠️ **Importante**: 
- Si Laravel está ejecutándose, el archivo SQLite puede estar bloqueado
- Si necesitas hacer cambios en la base de datos mientras Laravel está corriendo, ten cuidado con las transacciones
- Para desarrollo, es recomendable cerrar el servidor Laravel (`php artisan serve`) antes de hacer cambios importantes directamente en la base de datos

### Solución de Problemas

**Error: "Database file is locked"**
- El archivo está siendo usado por Laravel
- Cierra el servidor Laravel (`Ctrl+C` en la terminal donde está corriendo)
- O cierra DataGrip y vuelve a abrirlo

**Error: "File not found"**
- Verifica que la ruta sea correcta: `C:\laragon\www\Proyecto Contabilidad\database\database.sqlite`
- Asegúrate de que el archivo existe (puede estar en `database.sqlite` o `database/database.sqlite`)

**Error: "Unable to open database file"**
- Verifica los permisos del archivo
- Asegúrate de tener permisos de lectura/escritura en la carpeta `database`



