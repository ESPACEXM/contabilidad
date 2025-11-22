# Guía de Usuario: Análisis Financiero
## Manual de Uso del Sistema

---

## Introducción

Esta guía explica paso a paso cómo utilizar el módulo de Análisis Financiero del Sistema de Gestión Contable para calcular VAN, TIR y Punto de Equilibrio.

---

## Tabla de Contenidos

1. [Acceso al Módulo](#1-acceso-al-módulo)
2. [Crear Análisis VAN](#2-crear-análisis-van)
3. [Crear Análisis TIR](#3-crear-análisis-tir)
4. [Crear Análisis de Punto de Equilibrio](#4-crear-análisis-de-punto-de-equilibrio)
5. [Ver Resultados](#5-ver-resultados)
6. [Interpretar Resultados](#6-interpretar-resultados)

---

## 1. Acceso al Módulo

### Paso 1: Iniciar Sesión
1. Abrir el navegador y acceder a: `http://127.0.0.1:8000`
2. Ingresar credenciales:
   - **Email:** admin@demo.com
   - **Contraseña:** password

### Paso 2: Navegar al Módulo
1. En el menú lateral, buscar la sección **"Análisis"**
2. Hacer clic en **"Análisis Financiero (VAN, TIR, Punto Equilibrio)"**

---

## 2. Crear Análisis VAN

### ¿Qué es el VAN?
El Valor Actual Neto mide la rentabilidad de un proyecto descontando los flujos futuros a valor presente.

### Pasos para Crear un Análisis VAN:

#### Paso 1: Seleccionar Tipo de Análisis
1. En la página principal, hacer clic en **"Nuevo Análisis"**
2. O hacer clic en la tarjeta **"VAN"**
3. Se abrirá el formulario con el tipo "VAN" preseleccionado

#### Paso 2: Completar Información Básica
- **Nombre del Análisis:** Ejemplo: "Proyecto Expansión Planta 2024"
- **Tipo de Análisis:** Debe estar seleccionado "VAN - Valor Actual Neto"

#### Paso 3: Ingresar Datos de Inversión
- **Inversión Inicial:** Monto total a invertir (ej: 200000)
- **Tasa de Descuento:** Porcentaje anual (ej: 15 para 15%)

#### Paso 4: Ingresar Flujos de Efectivo
- En el campo **"Flujos de Efectivo"**, ingresar los valores separados por comas
- **Ejemplo:** `60000, 80000, 100000, 70000`
- Cada valor representa el flujo de efectivo de un período (año, mes, etc.)

#### Paso 5: Agregar Notas (Opcional)
- Puede agregar notas explicativas sobre el proyecto

#### Paso 6: Guardar
- Hacer clic en **"Crear Análisis"**
- El sistema calculará automáticamente el VAN

### Ejemplo Completo:

**Datos de Entrada:**
```
Nombre: Proyecto Nueva Maquinaria
Inversión Inicial: 150000
Tasa de Descuento: 12
Flujos: 50000, 60000, 70000, 50000
```

**Resultado Esperado:**
- VAN: $8,234.56 (aproximado)
- **Interpretación:** Proyecto VIABLE (VAN > 0)

---

## 3. Crear Análisis TIR

### ¿Qué es la TIR?
La Tasa Interna de Retorno es el porcentaje de rentabilidad que genera un proyecto.

### Pasos para Crear un Análisis TIR:

#### Paso 1: Seleccionar Tipo
1. Hacer clic en **"Nuevo Análisis"**
2. Seleccionar **"TIR - Tasa Interna de Retorno"**

#### Paso 2: Completar Datos
- **Nombre:** Ejemplo: "Análisis Rentabilidad Proyecto A"
- **Inversión Inicial:** Monto invertido
- **Flujos de Efectivo:** Valores separados por comas
- **Nota:** La TIR NO requiere tasa de descuento (la calcula)

#### Paso 3: Guardar
- Hacer clic en **"Crear Análisis"**

### Ejemplo Completo:

**Datos de Entrada:**
```
Nombre: Análisis TIR Proyecto B
Inversión Inicial: 100000
Flujos: 30000, 40000, 50000, 20000
```

**Resultado Esperado:**
- TIR: 15.2%
- **Interpretación:** El proyecto genera 15.2% de rentabilidad anual

---

## 4. Crear Análisis de Punto de Equilibrio

### ¿Qué es el Punto de Equilibrio?
Es el nivel de ventas necesario para cubrir todos los costos (utilidad = 0).

### Pasos para Crear un Análisis de Punto de Equilibrio:

#### Paso 1: Seleccionar Tipo
1. Hacer clic en **"Nuevo Análisis"**
2. Seleccionar **"Punto de Equilibrio"**

#### Paso 2: Completar Datos de Costos
- **Costos Fijos:** Gastos que no varían con la producción (ej: 50000)
- **Costo Variable por Unidad:** Costo por cada unidad producida (ej: 30)
- **Precio de Venta por Unidad:** Precio al que se vende cada unidad (ej: 80)

#### Paso 3: Guardar
- Hacer clic en **"Crear Análisis"**

### Ejemplo Completo:

**Datos de Entrada:**
```
Nombre: Punto Equilibrio Producto X
Costos Fijos: 50000
Costo Variable por Unidad: 30
Precio de Venta por Unidad: 80
```

**Resultado Esperado:**
- Unidades: 1,000 unidades
- Monto: $80,000
- **Interpretación:** Debe vender 1,000 unidades o $80,000 para cubrir costos

---

## 5. Ver Resultados

### Acceso a Resultados:
1. En la lista de análisis, hacer clic en **"Ver"** junto al análisis deseado
2. Se mostrará una página detallada con:
   - Información del análisis
   - Datos ingresados
   - Resultados calculados
   - Interpretación y recomendaciones

### Información Mostrada:

#### Para VAN:
- Valor Actual Neto calculado
- Indicador visual (verde si viable, rojo si no viable)
- Recomendación: "Proyecto viable - Se recomienda invertir" o "Proyecto no viable"
- Interpretación del resultado

#### Para TIR:
- Tasa Interna de Retorno en porcentaje
- Comparación con tasa de descuento (si aplica)
- Recomendación de viabilidad

#### Para Punto de Equilibrio:
- Unidades necesarias para equilibrio
- Monto en dinero necesario
- Margen de contribución unitario
- Porcentaje de contribución
- Interpretación práctica

---

## 6. Interpretar Resultados

### 6.1 Interpretación del VAN

**VAN Positivo (> 0):**
- ✅ **ACEPTAR** el proyecto
- El proyecto genera valor adicional
- Los flujos futuros superan la inversión y el costo de capital

**VAN Negativo (< 0):**
- ❌ **RECHAZAR** el proyecto
- El proyecto no genera suficiente retorno
- Los flujos futuros no cubren la inversión y el costo de capital

**VAN Cero (= 0):**
- ⚠️ **INDIFERENTE**
- El proyecto genera exactamente el retorno requerido
- No genera valor adicional

### 6.2 Interpretación de la TIR

**TIR > Tasa de Descuento:**
- ✅ **ACEPTAR** el proyecto
- El proyecto genera más rentabilidad que el costo de capital
- Ejemplo: TIR 18% > Tasa 12% = Proyecto viable

**TIR < Tasa de Descuento:**
- ❌ **RECHAZAR** el proyecto
- El proyecto no alcanza la rentabilidad mínima requerida
- Ejemplo: TIR 8% < Tasa 12% = Proyecto no viable

**TIR = Tasa de Descuento:**
- ⚠️ **INDIFERENTE**
- El proyecto genera exactamente la rentabilidad requerida

### 6.3 Interpretación del Punto de Equilibrio

**Ventas Actuales > Punto de Equilibrio:**
- ✅ La empresa genera **UTILIDADES**
- Mientras más lejos del punto de equilibrio, mayor la utilidad

**Ventas Actuales = Punto de Equilibrio:**
- ⚠️ La empresa **NO GANA NI PIERDE**
- Utilidad = $0

**Ventas Actuales < Punto de Equilibrio:**
- ❌ La empresa opera con **PÉRDIDAS**
- Necesita aumentar ventas o reducir costos

**Cálculo de Margen de Seguridad:**
```
Margen de Seguridad = (Ventas Actuales - PE) / Ventas Actuales × 100
```

**Ejemplo:**
- Ventas actuales: $120,000
- Punto de equilibrio: $80,000
- Margen de seguridad: (120,000 - 80,000) / 120,000 × 100 = **33.33%**

Esto significa que las ventas pueden caer 33.33% antes de llegar al punto de equilibrio.

---

## 7. Casos de Uso Prácticos

### Caso 1: Evaluar Compra de Equipo

**Situación:** Decidir si comprar nueva maquinaria

**Análisis Recomendado:** VAN
- Inversión inicial: Precio de la maquinaria
- Flujos: Ahorros o ingresos adicionales por año
- Tasa de descuento: Costo de capital de la empresa

### Caso 2: Comparar Dos Proyectos

**Situación:** Elegir entre Proyecto A y Proyecto B

**Análisis Recomendado:** TIR
- Calcular TIR de ambos proyectos
- Comparar TIRs
- Elegir el proyecto con mayor TIR (si ambos superan la tasa mínima)

### Caso 3: Planificar Ventas Mínimas

**Situación:** Determinar cuánto debe vender para no perder

**Análisis Recomendado:** Punto de Equilibrio
- Ingresar costos fijos y variables
- Obtener unidades y monto mínimo de ventas
- Usar para establecer metas de ventas

---

## 8. Preguntas Frecuentes

### ¿Puedo editar un análisis después de crearlo?
Actualmente el sistema permite crear y ver análisis. Para modificaciones, se recomienda crear un nuevo análisis.

### ¿Qué pasa si ingreso flujos negativos?
Los flujos negativos son válidos y representan salidas de efectivo. El sistema los procesará correctamente.

### ¿Cuántos períodos puedo analizar?
No hay límite técnico, pero se recomienda entre 3 y 10 períodos para análisis prácticos.

### ¿El punto de equilibrio considera impuestos?
No, el punto de equilibrio básico considera solo costos y precios. Para análisis más complejos, se recomienda ajustar los costos fijos para incluir impuestos.

### ¿Puedo exportar los resultados?
Actualmente los resultados se muestran en pantalla. Se puede usar la función de impresión del navegador para generar PDFs.

---

## 9. Consejos y Mejores Prácticas

1. **Datos Realistas:** Asegúrate de usar datos realistas y actualizados
2. **Documentación:** Usa el campo "Notas" para documentar supuestos y consideraciones
3. **Actualización:** Revisa y actualiza los análisis periódicamente
4. **Comparación:** Usa múltiples métodos (VAN y TIR) para validar decisiones
5. **Análisis de Sensibilidad:** Crea varios escenarios (optimista, realista, pesimista)

---

**Versión:** 1.0  
**Última actualización:** 2024

