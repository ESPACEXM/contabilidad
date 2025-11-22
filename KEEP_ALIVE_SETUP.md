# 🚀 Configuración Rápida: Keep-Alive para Render

## ⚡ Solución en 2 Minutos

### Opción 1: Cron-Job.org (Más Rápido)

1. **Ir a**: https://cron-job.org
2. **Pegar URL**: `https://tu-app.onrender.com/up`
   - O usar: `https://tu-app.onrender.com/ping`
3. **Configurar**:
   - Intervalo: **10 minutos**
   - Método: **GET**
4. **Guardar** y ¡Listo!

### Opción 2: UptimeRobot (Recomendado)

1. **Crear cuenta**: https://uptimerobot.com
2. **Agregar Monitor**:
   - Tipo: **HTTP(s)**
   - URL: `https://tu-app.onrender.com/up`
   - Intervalo: **5 minutos**
   - Nombre: "Render Keep-Alive"
3. **Guardar** y activar

## 📍 Endpoints Disponibles

Tu aplicación tiene 2 endpoints para keep-alive:

1. **`/up`** (Laravel Health Check)
   - Endpoint oficial de Laravel 11
   - Respuesta: `{"status":"ok"}`

2. **`/ping`** (Personalizado)
   - Endpoint adicional con timestamp
   - Respuesta: `{"status":"ok","timestamp":"...","message":"Server is alive"}`

## ✅ Verificación

Después de configurar, verifica que funcione:

```bash
# Probar endpoint
curl https://tu-app.onrender.com/up
curl https://tu-app.onrender.com/ping
```

Deberías recibir respuestas `200 OK`.

## ⚠️ Importante

- **Intervalo recomendado**: 5-10 minutos
- **No uses menos de 5 minutos** (puede ser considerado abuso)
- Render se "duerme" después de **15 minutos** de inactividad
- Con ping cada 10 minutos, tu app estará siempre activa

## 🎯 Resultado Esperado

Con esta configuración:
- ✅ Tu app nunca se "dormirá"
- ✅ Tiempo de respuesta siempre rápido
- ✅ Sin esperas de "cold start"
- ✅ Monitoreo automático de disponibilidad

