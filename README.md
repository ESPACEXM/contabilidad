# Sistema de Gestión Financiera y Contabilidad

Sistema completo de gestión financiera y contabilidad desarrollado con Laravel 11, diseñado para fines educativos y uso empresarial real.

## Características Principales

- ✅ Multi-tenancy: Soporte para múltiples empresas con separación completa de datos
- ✅ Sistema de autenticación con roles y permisos (Super Admin, Administrador, Contador, Empleado)
- ✅ Catálogo de Cuentas Contables completo con estructura jerárquica
- ✅ Dashboard interactivo con estadísticas y KPIs
- ✅ Interfaz moderna con TailwindCSS y tema claro/oscuro
- ✅ Responsive design para móvil, tablet y desktop

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- MySQL 5.7+ o PostgreSQL 12+
- Extensiones PHP: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

## Instalación

1. **Clonar o descargar el proyecto**

```bash
cd "C:\Users\posad\Desktop\Proyecto Contabilidad"
```

2. **Instalar dependencias de PHP**

```bash
composer install
```

3. **Instalar dependencias de Node.js**

```bash
npm install
```

4. **Configurar el archivo .env**

Copia el archivo `.env.example` y renómbralo a `.env`, luego configura las variables de entorno:

```bash
cp .env.example .env
```

Edita `.env` y configura:
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=contabilidad`
- `DB_USERNAME=root`
- `DB_PASSWORD=tu_contraseña`

5. **Generar clave de aplicación**

```bash
php artisan key:generate
```

6. **Ejecutar migraciones**

```bash
php artisan migrate
```

7. **Poblar base de datos con datos de ejemplo (opcional)**

```bash
php artisan db:seed
```

Esto creará:
- Roles del sistema (super-admin, administrador, contador, empleado)
- 2 empresas de ejemplo
- Usuarios de ejemplo:
  - `admin@demo.com` / `password`
  - `juan@minegocio.com` / `password`
- Catálogo de cuentas contables básico

8. **Compilar assets**

```bash
npm run build
```

O en modo desarrollo:

```bash
npm run dev
```

9. **Iniciar el servidor de desarrollo**

```bash
php artisan serve
```

El sistema estará disponible en: `http://localhost:8000`

## Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── ChartAccountController.php
│   │   │   ├── DashboardController.php
│   │   │   └── TenantController.php
│   │   └── Middleware/
│   │       └── HandleTenant.php
│   └── Models/
│       ├── ChartAccount.php
│       ├── Tenant.php
│       ├── User.php
│       └── AuditLog.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── ChartAccountSeeder.php
│       ├── RoleSeeder.php
│       └── TenantSeeder.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── chart-accounts/
│   │   ├── layouts/
│   │   └── dashboard.blade.php
│   ├── css/
│   └── js/
└── routes/
    └── web.php
```

## Uso

### Primer Acceso

1. Ve a `http://localhost:8000`
2. Haz clic en "Regístrate" para crear una nueva empresa y usuario administrador
3. O usa las credenciales de ejemplo: `admin@demo.com` / `password`

### Crear una Cuenta Contable

1. Ve al menú lateral y selecciona "Catálogo de Cuentas"
2. Haz clic en "Nueva Cuenta"
3. Completa el formulario:
   - **Código**: Identificador único (ej: 1.01.01)
   - **Nombre**: Nombre de la cuenta (ej: Caja)
   - **Tipo**: Activo, Pasivo, Capital, Ingreso, Egreso
   - **Naturaleza**: Deudora o Acreedora
   - **Nivel**: Cuenta Mayor, Subcuenta o Auxiliar
   - **Cuenta Padre**: Opcional, para crear jerarquías

### Sistema Multi-Tenant

Cada empresa (tenant) tiene:
- Sus propios usuarios
- Su propio catálogo de cuentas
- Separación completa de datos

El middleware `HandleTenant` asegura que cada usuario solo acceda a los datos de su empresa.

## Roles y Permisos

- **Super Admin**: Acceso completo, puede gestionar todas las empresas
- **Administrador**: Gestión completa dentro de su empresa
- **Contador**: Puede ver y crear/editar cuentas contables
- **Empleado**: Solo lectura de cuentas contables

## Próximos Módulos (Por Implementar)

- Control de Inventario
- Presupuestos
- Estados Financieros
- Análisis Vertical y Horizontal
- Análisis Financiero (VAN, TIR, Punto de Equilibrio)
- Proyecciones Financieras

## Soporte

Para problemas o preguntas, consulta la documentación de Laravel: https://laravel.com/docs/11.x

## Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

