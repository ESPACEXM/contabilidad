# Guía: Mantener Render Siempre Activo (Keep-Alive)

## 🎯 Problema
Los servicios gratuitos de Render se "duermen" después de 15 minutos de inactividad. Necesitas un servicio que haga ping periódicamente para mantenerlo activo.

## ✅ Soluciones Recomendadas

### 1. **UptimeRobot** (⭐ RECOMENDADO - Gratis)
- **URL**: https://uptimerobot.com
- **Gratis**: Hasta 50 monitores
- **Intervalo mínimo**: 5 minutos
- **Características**:
  - Alertas por email/SMS
  - Dashboard con estadísticas
  - Múltiples tipos de monitoreo

**Configuración:**
1. Crear cuenta en UptimeRobot
2. Agregar nuevo monitor:
   - **Tipo**: HTTP(s)
   - **URL**: `https://tu-app.onrender.com/up`
   - **Intervalo**: 5 minutos
   - **Nombre**: "Render Keep-Alive"

### 2. **Cron-Job.org** (Gratis)
- **URL**: https://cron-job.org
- **Gratis**: Hasta 2 jobs simultáneos
- **Intervalo mínimo**: 1 minuto
- **Características**:
  - Muy simple de configurar
  - No requiere cuenta para pruebas

**Configuración:**
1. Ir a https://cron-job.org
2. Crear nuevo job:
   - **URL**: `https://tu-app.onrender.com/up`
   - **Intervalo**: Cada 10 minutos
   - **Método**: GET

### 3. **Pingdom** (Pago, pero tiene plan gratis limitado)
- **URL**: https://www.pingdom.com
- **Gratis**: 1 monitor
- **Intervalo mínimo**: 1 minuto
- **Características**:
  - Muy confiable
  - Alertas avanzadas

### 4. **Better Uptime** (Gratis con limitaciones)
- **URL**: https://betteruptime.com
- **Gratis**: 10 monitores
- **Intervalo mínimo**: 30 segundos
- **Características**:
  - Interfaz moderna
  - Alertas por múltiples canales

### 5. **StatusCake** (Gratis)
- **URL**: https://www.statuscake.com
- **Gratis**: 10 tests
- **Intervalo mínimo**: 5 minutos
- **Características**:
  - Buena interfaz
  - Alertas configurables

## 🔧 Endpoints Disponibles en tu App

### Endpoint de Health Check (Laravel 11)
```
GET https://tu-app.onrender.com/up
```
**Respuesta esperada**: `200 OK` con JSON `{"status":"ok"}`

### Endpoint de Ping Personalizado (si lo creas)
```
GET https://tu-app.onrender.com/ping
```
**Respuesta esperada**: `200 OK` con JSON `{"status":"ok","timestamp":"..."}`

## 📝 Configuración Recomendada

### Para UptimeRobot:
1. **Tipo de Monitor**: HTTP(s)
2. **URL**: `https://tu-app.onrender.com/up`
3. **Intervalo**: 5 minutos
4. **Timeout**: 30 segundos
5. **Alertas**: Email cuando falle

### Para Cron-Job.org:
1. **URL**: `https://tu-app.onrender.com/up`
2. **Intervalo**: Cada 10 minutos
3. **Método**: GET
4. **Timeout**: 30 segundos

## ⚠️ Consideraciones

1. **No abuses**: No configures intervalos muy cortos (< 5 minutos) para no sobrecargar el servicio
2. **Múltiples servicios**: Puedes usar 2-3 servicios diferentes como respaldo
3. **Monitoreo real**: Estos servicios también te alertan si tu app está caída

## 🚀 Solución Rápida (5 minutos)

**Opción más rápida: Cron-Job.org**

1. Ve a https://cron-job.org
2. Pega tu URL: `https://tu-app.onrender.com/up`
3. Configura: Cada 10 minutos
4. ¡Listo!

## 💡 Alternativa: Script Local

Si tienes una computadora siempre encendida, puedes crear un script que haga ping:

**Windows (PowerShell):**
```powershell
# Guardar como keep-alive.ps1
while ($true) {
    Invoke-WebRequest -Uri "https://tu-app.onrender.com/up" -UseBasicParsing
    Start-Sleep -Seconds 600  # 10 minutos
}
```

**Linux/Mac:**
```bash
# Guardar como keep-alive.sh
#!/bin/bash
while true; do
    curl https://tu-app.onrender.com/up
    sleep 600  # 10 minutos
done
```

## 📊 Recomendación Final

**Para la mayoría de usuarios: UptimeRobot**
- ✅ Gratis
- ✅ Confiable
- ✅ Fácil de configurar
- ✅ Alertas útiles
- ✅ Dashboard informativo

**Para máxima simplicidad: Cron-Job.org**
- ✅ Muy simple
- ✅ No requiere cuenta para probar
- ✅ Funciona inmediatamente

