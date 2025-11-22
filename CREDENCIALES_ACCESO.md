# Credenciales de Acceso al Sistema

## 🔐 Usuarios Creados por los Seeders

### Usuario Administrador Principal

**Email:** `admin@demo.com`  
**Contraseña:** `password`  
**Rol:** Administrador  
**Empresa:** Empresa Demo S.A.

### Usuario Administrador Secundario

**Email:** `juan@minegocio.com`  
**Contraseña:** `password`  
**Rol:** Administrador  
**Empresa:** Mi Negocio S.A.

## 📋 Datos Iniciales Creados

### 1. Roles y Permisos
- ✅ `super-admin` - Acceso total al sistema
- ✅ `administrador` - Administración de la empresa
- ✅ `contador` - Acceso contable
- ✅ `empleado` - Acceso limitado

### 2. Empresas (Tenants)
- ✅ **Empresa Demo S.A.**
  - Email: demo@empresa.com
  - RFC: DEM123456ABC
  - Moneda: MXN
  
- ✅ **Mi Negocio S.A.**
  - Email: contacto@minegocio.com
  - Moneda: MXN

### 3. Catálogo de Cuentas
Para cada empresa se crean las siguientes cuentas:

**Activos:**
- Caja (1.1.01) - Saldo inicial: $50,000
- Bancos (1.1.02) - Saldo inicial: $150,000
- Clientes (1.1.03) - Saldo inicial: $75,000

**Pasivos:**
- Proveedores (2.1.01) - Saldo inicial: $45,000

**Capital:**
- Capital Social (3.1) - Saldo inicial: $200,000

**Ingresos:**
- Ventas (4.1)

**Egresos:**
- Gastos de Operación (5.1)

## 🚀 Cómo Acceder

1. Ve a: `https://contabilidad-1-o9f5.onrender.com/login`
2. Ingresa:
   - **Email:** `admin@demo.com`
   - **Contraseña:** `password`
3. Haz clic en "Iniciar Sesión"

## ⚠️ Importante

- Los seeders se ejecutan automáticamente en cada deploy
- Si los datos ya existen, no se duplican (seeders idempotentes)
- Puedes cambiar la contraseña desde el perfil de usuario después de iniciar sesión

## 🔄 Si No Puedes Iniciar Sesión

1. Verifica que los seeders se ejecutaron correctamente en los logs de Render
2. Verifica que la base de datos SQLite se creó correctamente
3. Verifica que el usuario existe en la base de datos
4. Intenta con el otro usuario: `juan@minegocio.com` / `password`

