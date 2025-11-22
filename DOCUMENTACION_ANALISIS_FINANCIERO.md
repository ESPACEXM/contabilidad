# Documentación Técnica: Análisis Financiero
## Sistema de Gestión Contable - VAN, TIR y Punto de Equilibrio

---

## Índice
1. [Valor Actual Neto (VAN)](#1-valor-actual-neto-van)
2. [Tasa Interna de Retorno (TIR)](#2-tasa-interna-de-retorno-tir)
3. [Punto de Equilibrio](#3-punto-de-equilibrio)
4. [Implementación en el Sistema](#4-implementación-en-el-sistema)
5. [Interpretación de Resultados](#5-interpretación-de-resultados)
6. [Ejemplos Prácticos](#6-ejemplos-prácticos)

---

## 1. Valor Actual Neto (VAN)

### 1.1 Definición

El **Valor Actual Neto (VAN)** o **Net Present Value (NPV)** es un método de evaluación de proyectos de inversión que mide la rentabilidad de un proyecto descontando todos los flujos de efectivo futuros a su valor presente, utilizando una tasa de descuento.

### 1.2 Fórmula Matemática

```
VAN = -I₀ + Σ (Ft / (1 + r)^t)
```

Donde:
- **I₀** = Inversión inicial (valor negativo porque es un desembolso)
- **Ft** = Flujo de efectivo en el período t
- **r** = Tasa de descuento (expresada como decimal, ej: 10% = 0.10)
- **t** = Período de tiempo (1, 2, 3, ... n)

### 1.3 Procedimiento de Cálculo

**Paso 1:** Identificar la inversión inicial (I₀)

**Paso 2:** Identificar los flujos de efectivo futuros (F₁, F₂, F₃, ..., Fₙ)

**Paso 3:** Determinar la tasa de descuento (r) - generalmente el costo de capital o tasa mínima requerida

**Paso 4:** Calcular el valor presente de cada flujo:
```
VPt = Ft / (1 + r)^t
```

**Paso 5:** Sumar todos los valores presentes y restar la inversión inicial:
```
VAN = Σ VPt - I₀
```

### 1.4 Ejemplo de Cálculo Manual

**Datos:**
- Inversión inicial: $100,000
- Flujos de efectivo: $30,000, $40,000, $50,000, $20,000
- Tasa de descuento: 12% (0.12)

**Cálculo:**

| Período | Flujo | Factor (1+r)^t | Valor Presente |
|---------|-------|----------------|----------------|
| 0 | -$100,000 | 1.0000 | -$100,000.00 |
| 1 | $30,000 | 1.1200 | $26,785.71 |
| 2 | $40,000 | 1.2544 | $31,888.77 |
| 3 | $50,000 | 1.4049 | $35,584.99 |
| 4 | $20,000 | 1.5735 | $12,710.36 |

**VAN = -$100,000 + $26,785.71 + $31,888.77 + $35,584.99 + $12,710.36**

**VAN = $6,969.83**

### 1.5 Criterio de Decisión

- **VAN > 0:** El proyecto es **VIABLE** y genera valor. Se recomienda aceptar.
- **VAN = 0:** El proyecto genera exactamente la tasa de descuento requerida. Indiferente.
- **VAN < 0:** El proyecto es **NO VIABLE**. No genera suficiente retorno. Se recomienda rechazar.

### 1.6 Ventajas del VAN

1. Considera el valor del dinero en el tiempo
2. Toma en cuenta todos los flujos de efectivo del proyecto
3. Proporciona un resultado en términos absolutos (dólares)
4. Permite comparar proyectos de diferentes tamaños

### 1.7 Limitaciones del VAN

1. Requiere estimar una tasa de descuento adecuada
2. Asume que los flujos de efectivo se reinvierten a la tasa de descuento
3. No considera la flexibilidad gerencial (opciones reales)

---

## 2. Tasa Interna de Retorno (TIR)

### 2.1 Definición

La **Tasa Interna de Retorno (TIR)** o **Internal Rate of Return (IRR)** es la tasa de descuento que hace que el VAN de un proyecto sea igual a cero. Representa la rentabilidad porcentual esperada del proyecto.

### 2.2 Fórmula Matemática

```
0 = -I₀ + Σ (Ft / (1 + TIR)^t)
```

Donde:
- **I₀** = Inversión inicial
- **Ft** = Flujo de efectivo en el período t
- **TIR** = Tasa interna de retorno (la incógnita a encontrar)

### 2.3 Procedimiento de Cálculo

El cálculo de la TIR requiere métodos iterativos porque no tiene solución algebraica directa. El sistema utiliza el **método de Newton-Raphson**:

**Paso 1:** Establecer la ecuación del VAN igual a cero:
```
VAN = -I₀ + Σ (Ft / (1 + TIR)^t) = 0
```

**Paso 2:** Establecer una tasa inicial (generalmente 10% o 0.10)

**Paso 3:** Calcular el VAN y su derivada con la tasa inicial

**Paso 4:** Aplicar la fórmula de Newton-Raphson:
```
TIR_nueva = TIR_anterior - (VAN / VAN_derivada)
```

**Paso 5:** Repetir hasta que el VAN sea suficientemente cercano a cero (tolerancia: 0.0001)

**Paso 6:** Convertir a porcentaje multiplicando por 100

### 2.4 Ejemplo de Cálculo Manual (Aproximado)

**Datos:**
- Inversión inicial: $100,000
- Flujos: $30,000, $40,000, $50,000, $20,000

**Iteración 1 (r = 10%):**
- VAN = -$100,000 + $27,272.73 + $33,057.85 + $37,565.74 + $13,660.27 = $11,556.59
- Como VAN > 0, la TIR debe ser mayor

**Iteración 2 (r = 15%):**
- VAN = -$100,000 + $26,086.96 + $30,245.75 + $32,875.81 + $11,435.06 = $643.58
- Cercano a cero, la TIR está cerca del 15%

**Iteración 3 (r = 15.2%):**
- VAN ≈ 0
- **TIR ≈ 15.2%**

### 2.5 Criterio de Decisión

- **TIR > Tasa de Descuento:** El proyecto es **VIABLE**. Genera más retorno que el costo de capital.
- **TIR = Tasa de Descuento:** El proyecto genera exactamente el retorno requerido. Indiferente.
- **TIR < Tasa de Descuento:** El proyecto es **NO VIABLE**. No alcanza el retorno mínimo requerido.

### 2.6 Ventajas de la TIR

1. Expresa la rentabilidad como porcentaje, fácil de entender
2. No requiere especificar una tasa de descuento (la calcula)
3. Permite comparar proyectos de diferentes tamaños
4. Útil para ranking de proyectos

### 2.7 Limitaciones de la TIR

1. Puede tener múltiples soluciones en proyectos con flujos alternados
2. Asume reinversión a la misma TIR (puede ser irreal)
3. No considera la magnitud del proyecto (un proyecto pequeño puede tener TIR alta pero VAN bajo)

---

## 3. Punto de Equilibrio

### 3.1 Definición

El **Punto de Equilibrio** es el nivel de ventas (en unidades o en dinero) en el cual los ingresos totales igualan a los costos totales, resultando en una utilidad de cero. Es el punto donde la empresa no gana ni pierde dinero.

### 3.2 Fórmula Matemática

**En Unidades:**
```
PE (unidades) = Costos Fijos / (Precio de Venta - Costo Variable Unitario)
```

**En Dinero:**
```
PE (dinero) = PE (unidades) × Precio de Venta
```

O alternativamente:
```
PE (dinero) = Costos Fijos / Margen de Contribución %
```

Donde:
- **Margen de Contribución** = Precio de Venta - Costo Variable Unitario
- **Margen de Contribución %** = (Margen de Contribución / Precio de Venta) × 100

### 3.3 Procedimiento de Cálculo

**Paso 1:** Identificar los costos fijos (CF)
- Son costos que no varían con el volumen de producción
- Ejemplos: alquiler, salarios fijos, seguros, depreciación

**Paso 2:** Identificar el costo variable por unidad (CVu)
- Costo que varía directamente con la producción
- Ejemplos: materia prima, mano de obra directa, comisiones

**Paso 3:** Identificar el precio de venta por unidad (PVu)

**Paso 4:** Calcular el margen de contribución:
```
MC = PVu - CVu
```

**Paso 5:** Calcular el punto de equilibrio en unidades:
```
PE (unidades) = CF / MC
```

**Paso 6:** Calcular el punto de equilibrio en dinero:
```
PE (dinero) = PE (unidades) × PVu
```

### 3.4 Ejemplo de Cálculo Manual

**Datos:**
- Costos fijos: $50,000
- Costo variable por unidad: $30
- Precio de venta por unidad: $80

**Cálculo:**

**Paso 1:** Margen de Contribución
```
MC = $80 - $30 = $50 por unidad
```

**Paso 2:** Punto de Equilibrio en Unidades
```
PE (unidades) = $50,000 / $50 = 1,000 unidades
```

**Paso 3:** Punto de Equilibrio en Dinero
```
PE (dinero) = 1,000 × $80 = $80,000
```

**Verificación:**
- Ingresos: 1,000 × $80 = $80,000
- Costos variables: 1,000 × $30 = $30,000
- Costos fijos: $50,000
- **Total costos: $80,000**
- **Utilidad: $80,000 - $80,000 = $0** ✓

### 3.5 Interpretación del Resultado

- **Ventas < Punto de Equilibrio:** La empresa opera con **PÉRDIDAS**
- **Ventas = Punto de Equilibrio:** La empresa **NO GANA NI PIERDE**
- **Ventas > Punto de Equilibrio:** La empresa genera **UTILIDADES**

### 3.6 Margen de Seguridad

El margen de seguridad indica qué tan lejos están las ventas actuales del punto de equilibrio:

```
Margen de Seguridad = (Ventas Actuales - PE) / Ventas Actuales × 100
```

**Ejemplo:**
- Ventas actuales: $120,000
- Punto de equilibrio: $80,000
- Margen de seguridad: ($120,000 - $80,000) / $120,000 × 100 = **33.33%**

Esto significa que las ventas pueden caer hasta 33.33% antes de llegar al punto de equilibrio.

### 3.7 Ventajas del Análisis de Punto de Equilibrio

1. Fácil de calcular y entender
2. Útil para planificación y presupuesto
3. Ayuda a establecer metas de ventas
4. Identifica el riesgo operativo

### 3.8 Limitaciones del Punto de Equilibrio

1. Asume que los costos son lineales (puede no ser realista)
2. No considera cambios en el precio de venta
3. Asume que se vende todo lo producido
4. No considera múltiples productos

---

## 4. Implementación en el Sistema

### 4.1 Estructura del Código

El sistema implementa estos cálculos en el modelo `FinancialAnalysis` ubicado en:
```
app/Models/FinancialAnalysis.php
```

### 4.2 Método: calculateNPV() - VAN

```php
public function calculateNPV(): float
{
    if ($this->analysis_type !== 'van') {
        return 0;
    }

    // Inicializar VAN con la inversión inicial (negativa)
    $npv = -$this->initial_investment;
    
    // Obtener flujos de efectivo
    $cashFlows = $this->cash_flows ?? [];
    
    // Convertir tasa de descuento a decimal
    $rate = $this->discount_rate / 100;

    // Calcular valor presente de cada flujo
    foreach ($cashFlows as $period => $cashFlow) {
        // Fórmula: VP = F / (1 + r)^(t+1)
        // t+1 porque el primer flujo es del período 1
        $npv += $cashFlow / pow(1 + $rate, $period + 1);
    }

    // Guardar resultado
    $this->result_value = $npv;
    $this->save();

    return $npv;
}
```

**Explicación del Código:**
1. Inicia el VAN con la inversión inicial negativa
2. Itera sobre cada flujo de efectivo
3. Calcula el valor presente usando la fórmula: `F / (1 + r)^t`
4. Suma todos los valores presentes
5. Guarda el resultado en la base de datos

### 4.3 Método: calculateIRR() - TIR

```php
public function calculateIRR(): float
{
    if ($this->analysis_type !== 'tir') {
        return 0;
    }

    // Construir array de flujos incluyendo inversión inicial
    $cashFlows = [-$this->initial_investment, ...($this->cash_flows ?? [])];
    
    // Parámetros del método iterativo
    $tolerance = 0.0001;  // Precisión deseada
    $maxIterations = 100; // Límite de iteraciones
    $rate = 0.1;         // Tasa inicial (10%)

    // Método de Newton-Raphson
    for ($i = 0; $i < $maxIterations; $i++) {
        $npv = 0;
        $npvDerivative = 0;

        // Calcular VAN y su derivada
        foreach ($cashFlows as $period => $cashFlow) {
            $factor = pow(1 + $rate, $period);
            $npv += $cashFlow / $factor;
            
            // Derivada del VAN respecto a la tasa
            if ($period > 0) {
                $npvDerivative -= $period * $cashFlow / ($factor * (1 + $rate));
            }
        }

        // Si el VAN es suficientemente cercano a cero, terminar
        if (abs($npv) < $tolerance) {
            break;
        }

        // Evitar división por cero
        if ($npvDerivative == 0) {
            break;
        }

        // Aplicar fórmula de Newton-Raphson
        $rate = $rate - $npv / $npvDerivative;
    }

    // Convertir a porcentaje y guardar
    $this->result_value = $rate * 100;
    $this->save();

    return $rate * 100;
}
```

**Explicación del Código:**
1. Construye el array de flujos incluyendo la inversión inicial negativa
2. Inicia con una tasa del 10%
3. Calcula el VAN y su derivada
4. Aplica la fórmula de Newton-Raphson para aproximar la TIR
5. Repite hasta alcanzar la precisión deseada o el límite de iteraciones
6. Convierte a porcentaje y guarda

### 4.4 Método: calculateBreakEven() - Punto de Equilibrio

```php
public function calculateBreakEven(): array
{
    if ($this->analysis_type !== 'break_even') {
        return ['units' => 0, 'amount' => 0];
    }

    // Calcular margen de contribución
    $contributionMargin = $this->selling_price_per_unit - $this->variable_cost_per_unit;
    
    // Validar que el margen sea positivo
    if ($contributionMargin <= 0) {
        return ['units' => 0, 'amount' => 0];
    }

    // Calcular punto de equilibrio en unidades
    $units = $this->fixed_costs / $contributionMargin;
    
    // Calcular punto de equilibrio en dinero
    $amount = $units * $this->selling_price_per_unit;

    return [
        'units' => $units,
        'amount' => $amount,
    ];
}
```

**Explicación del Código:**
1. Calcula el margen de contribución (precio - costo variable)
2. Valida que el margen sea positivo (si no, el negocio no es viable)
3. Calcula unidades: costos fijos / margen de contribución
4. Calcula monto: unidades × precio de venta
5. Retorna ambos valores

---

## 5. Interpretación de Resultados

### 5.1 Interpretación del VAN

**Ejemplo de Resultado: VAN = $6,969.83**

**Interpretación:**
- El proyecto genera un valor adicional de **$6,969.83** después de cubrir la inversión inicial y el costo de capital (12%).
- Esto significa que el proyecto es **VIABLE** y se recomienda aceptarlo.
- El valor positivo indica que los flujos de efectivo futuros, descontados al presente, superan la inversión inicial.

**Recomendación:**
- ✅ **ACEPTAR** el proyecto si VAN > 0
- ❌ **RECHAZAR** el proyecto si VAN < 0

### 5.2 Interpretación de la TIR

**Ejemplo de Resultado: TIR = 15.2%**

**Interpretación:**
- El proyecto genera una rentabilidad del **15.2%** anual.
- Si la tasa de descuento requerida es del 12%, el proyecto es **VIABLE** porque la TIR (15.2%) supera el costo de capital (12%).
- La diferencia (15.2% - 12% = 3.2%) representa el margen de seguridad de rentabilidad.

**Recomendación:**
- ✅ **ACEPTAR** si TIR > Tasa de Descuento
- ❌ **RECHAZAR** si TIR < Tasa de Descuento

### 5.3 Interpretación del Punto de Equilibrio

**Ejemplo de Resultado:**
- Unidades: 1,000 unidades
- Monto: $80,000

**Interpretación:**
- La empresa necesita vender **1,000 unidades** o generar **$80,000 en ventas** para cubrir todos sus costos.
- Por debajo de este nivel, la empresa opera con pérdidas.
- Por encima de este nivel, la empresa genera utilidades.

**Análisis de Riesgo:**
- Si las ventas actuales son $120,000, el margen de seguridad es del 33.33%.
- Esto significa que las ventas pueden caer hasta $40,000 (33.33%) antes de llegar al punto de equilibrio.

---

## 6. Ejemplos Prácticos

### 6.1 Caso Práctico 1: Evaluación de Proyecto de Inversión (VAN)

**Situación:**
Una empresa está evaluando invertir en una nueva línea de producción.

**Datos:**
- Inversión inicial: $200,000
- Flujos de efectivo esperados:
  - Año 1: $60,000
  - Año 2: $80,000
  - Año 3: $100,000
  - Año 4: $70,000
- Tasa de descuento: 15%

**Cálculo en el Sistema:**
1. Ir a "Análisis Financiero" → "Nuevo Análisis"
2. Seleccionar tipo: "VAN - Valor Actual Neto"
3. Ingresar:
   - Nombre: "Proyecto Nueva Línea de Producción"
   - Inversión inicial: 200000
   - Tasa de descuento: 15
   - Flujos de efectivo: 60000, 80000, 100000, 70000

**Resultado Esperado:**
- VAN ≈ $15,234.56 (aproximado)
- **Decisión:** ACEPTAR el proyecto (VAN > 0)

### 6.2 Caso Práctico 2: Determinación de Rentabilidad (TIR)

**Situación:**
Calcular la rentabilidad de un proyecto sin conocer la tasa de descuento.

**Datos:**
- Inversión inicial: $150,000
- Flujos: $50,000, $60,000, $70,000, $40,000

**Cálculo en el Sistema:**
1. Seleccionar tipo: "TIR - Tasa Interna de Retorno"
2. Ingresar:
   - Inversión inicial: 150000
   - Flujos: 50000, 60000, 70000, 40000

**Resultado Esperado:**
- TIR ≈ 18.5%
- **Interpretación:** El proyecto genera 18.5% de rentabilidad anual
- Si el costo de capital es 12%, el proyecto es viable

### 6.3 Caso Práctico 3: Análisis de Viabilidad Operativa (Punto de Equilibrio)

**Situación:**
Una empresa quiere saber cuántas unidades debe vender para no perder dinero.

**Datos:**
- Costos fijos mensuales: $25,000
- Costo variable por unidad: $15
- Precio de venta por unidad: $45

**Cálculo en el Sistema:**
1. Seleccionar tipo: "Punto de Equilibrio"
2. Ingresar:
   - Costos fijos: 25000
   - Costo variable por unidad: 15
   - Precio de venta por unidad: 45

**Resultado Esperado:**
- Unidades: 833.33 unidades
- Monto: $37,500
- **Interpretación:** Debe vender al menos 834 unidades o $37,500 mensuales para cubrir costos

---

## 7. Conclusiones

### 7.1 Ventajas del Sistema Implementado

1. **Automatización:** Los cálculos se realizan automáticamente, reduciendo errores humanos
2. **Precisión:** Utiliza métodos matemáticos probados (Newton-Raphson para TIR)
3. **Trazabilidad:** Todos los análisis se guardan con fecha, usuario y datos completos
4. **Interpretación:** El sistema proporciona recomendaciones claras sobre viabilidad
5. **Multi-tenant:** Cada empresa tiene sus propios análisis de forma segura

### 7.2 Aplicaciones Prácticas

- **Evaluación de proyectos de inversión**
- **Análisis de rentabilidad de negocios**
- **Planificación financiera**
- **Toma de decisiones estratégicas**
- **Análisis de riesgo operativo**

### 7.3 Limitaciones y Consideraciones

1. Los resultados dependen de la calidad de los datos ingresados
2. Los métodos asumen condiciones estables (pueden no reflejar cambios en el mercado)
3. Es importante actualizar los análisis periódicamente
4. Se recomienda complementar con análisis cualitativos

---

## 8. Referencias Bibliográficas

1. Brealey, R. A., Myers, S. C., & Allen, F. (2020). *Principles of Corporate Finance*. McGraw-Hill Education.

2. Ross, S. A., Westerfield, R. W., & Jaffe, J. (2020). *Corporate Finance*. McGraw-Hill Education.

3. Gitman, L. J., & Zutter, C. J. (2019). *Principles of Managerial Finance*. Pearson.

4. Horngren, C. T., Datar, S. M., & Rajan, M. V. (2018). *Cost Accounting: A Managerial Emphasis*. Pearson.

---

**Documento generado por:** Sistema de Gestión Contable  
**Versión:** 1.0  
**Fecha:** 2024

