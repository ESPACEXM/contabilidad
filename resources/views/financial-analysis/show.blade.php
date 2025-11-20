@extends('layouts.app')

@section('title', 'Ver Análisis Financiero')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('financial-analysis.index') }}" class="text-gray-500 hover:text-gray-700">Análisis Financiero</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $financialAnalysis->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $financialAnalysis->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Tipo: <span class="font-semibold">{{ strtoupper($financialAnalysis->analysis_type) }}</span>
            </p>
        </div>
        <a href="{{ route('financial-analysis.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
            Volver
        </a>
    </div>

    <!-- Alerta si no hay datos -->
    @if(!$financialAnalysis->analysis_type || (!$financialAnalysis->initial_investment && $financialAnalysis->analysis_type !== 'break_even'))
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-yellow-800 dark:text-yellow-300 font-semibold">Análisis incompleto</p>
        </div>
        <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-2">
            Este análisis no tiene todos los datos necesarios. Para ver resultados, edita el análisis y completa los campos requeridos.
        </p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información General -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información del Análisis</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nombre del Análisis</p>
                            <p class="font-semibold text-lg text-gray-900 dark:text-white">
                                {{ $financialAnalysis->name ?? 'Sin nombre' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tipo de Análisis</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                @if($financialAnalysis->analysis_type === 'van')
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-sm">VAN - Valor Actual Neto</span>
                                @elseif($financialAnalysis->analysis_type === 'tir')
                                    <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded text-sm">TIR - Tasa Interna de Retorno</span>
                                @elseif($financialAnalysis->analysis_type === 'break_even')
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-sm">Punto de Equilibrio</span>
                                @elseif($financialAnalysis->analysis_type === 'payback')
                                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded text-sm">Periodo de Recuperación</span>
                                @elseif($financialAnalysis->analysis_type === 'profitability_index')
                                    <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded text-sm">Índice de Rentabilidad</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded text-sm">{{ ucfirst($financialAnalysis->analysis_type ?? 'No especificado') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Creado por</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $financialAnalysis->creator->name ?? ($financialAnalysis->created_by ? 'Usuario #' . $financialAnalysis->created_by : 'Sistema') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Creación</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $financialAnalysis->created_at ? $financialAnalysis->created_at->format('d/m/Y H:i') : ($financialAnalysis->updated_at ? $financialAnalysis->updated_at->format('d/m/Y H:i') : date('d/m/Y H:i')) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campos según el tipo de análisis -->
            @if(in_array($financialAnalysis->analysis_type, ['van', 'tir', 'payback', 'profitability_index']))
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Datos de Inversión</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Inversión Inicial</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($financialAnalysis->initial_investment ?? 0, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tasa de Descuento</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($financialAnalysis->discount_rate ?? 0, 2) }}%
                        </p>
                    </div>
                </div>

                @if(!empty($financialAnalysis->cash_flows) && is_array($financialAnalysis->cash_flows) && count($financialAnalysis->cash_flows) > 0)
                <div class="mt-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Flujos de Efectivo</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($financialAnalysis->cash_flows as $index => $flow)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-2 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Período {{ $index + 1 }}</p>
                            <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($flow, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Total de flujos: ${{ number_format(array_sum($financialAnalysis->cash_flows), 2) }}
                    </div>
                </div>
                @else
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        ⚠ No hay flujos de efectivo registrados para este análisis.
                    </p>
                </div>
                @endif
            </div>
            @endif

            @if($financialAnalysis->analysis_type === 'break_even')
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Datos del Punto de Equilibrio</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Costos Fijos</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($financialAnalysis->fixed_costs ?? 0, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Costo Variable/Unidad</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($financialAnalysis->variable_cost_per_unit ?? 0, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Precio Venta/Unidad</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($financialAnalysis->selling_price_per_unit ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($financialAnalysis->notes)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notas</h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $financialAnalysis->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Resultados -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Resultado del Análisis</h2>
                
                @if($financialAnalysis->analysis_type === 'van')
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Valor Actual Neto (VAN)</p>
                        @php
                            $npv = $results['npv'] ?? $financialAnalysis->result_value ?? 0;
                        @endphp
                        <p class="text-3xl font-bold {{ $npv >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($npv, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            {{ $npv >= 0 ? '✓ Proyecto viable - Se recomienda invertir' : '✗ Proyecto no viable - No se recomienda invertir' }}
                        </p>
                        @if($npv != 0)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded text-left text-xs">
                            <p class="font-semibold mb-1">Interpretación:</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                VAN > 0: El proyecto genera valor y supera la tasa de descuento del {{ number_format($financialAnalysis->discount_rate ?? 0, 2) }}%.
                            </p>
                        </div>
                        @endif
                    </div>
                @elseif($financialAnalysis->analysis_type === 'tir')
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Tasa Interna de Retorno (TIR)</p>
                        @php
                            $irr = $results['irr'] ?? $financialAnalysis->result_value ?? 0;
                        @endphp
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($irr, 2) }}%
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            {{ $irr >= ($financialAnalysis->discount_rate ?? 0) ? '✓ Proyecto viable' : '✗ Proyecto no viable' }}
                        </p>
                        @if($irr > 0 && $financialAnalysis->discount_rate > 0)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded text-left text-xs">
                            <p class="font-semibold mb-1">Comparación:</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                TIR: {{ number_format($irr, 2) }}% vs Tasa de Descuento: {{ number_format($financialAnalysis->discount_rate, 2) }}%
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                {{ $irr >= $financialAnalysis->discount_rate ? 'La TIR supera la tasa mínima requerida' : 'La TIR no alcanza la tasa mínima requerida' }}
                            </p>
                        </div>
                        @endif
                    </div>
                @elseif($financialAnalysis->analysis_type === 'break_even')
                    <div class="space-y-4">
                        @php
                            $breakEven = $results['break_even'] ?? $financialAnalysis->calculateBreakEven();
                        @endphp
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Unidades de Equilibrio</p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ number_format($breakEven['units'] ?? 0, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">unidades</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Monto de Equilibrio</p>
                            <p class="text-2xl font-bold text-blue-600">
                                ${{ number_format($breakEven['amount'] ?? 0, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">en ventas</p>
                        </div>
                        @if(($breakEven['units'] ?? 0) > 0)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded text-left text-xs">
                            <p class="font-semibold mb-1">Interpretación:</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                Necesitas vender {{ number_format($breakEven['units'], 0) }} unidades o ${{ number_format($breakEven['amount'], 2) }} para cubrir todos los costos.
                            </p>
                        </div>
                        @endif
                    </div>
                @elseif($financialAnalysis->analysis_type === 'payback')
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Periodo de Recuperación</p>
                        @php
                            $payback = $results['payback_period'] ?? $financialAnalysis->calculatePaybackPeriod() ?? 0;
                        @endphp
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($payback, 1) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">períodos</p>
                        @if($payback > 0)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded text-left text-xs">
                            <p class="font-semibold mb-1">Interpretación:</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                El proyecto recuperará la inversión inicial en {{ number_format($payback, 1) }} períodos.
                            </p>
                        </div>
                        @endif
                    </div>
                @elseif($financialAnalysis->analysis_type === 'profitability_index')
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Índice de Rentabilidad</p>
                        @php
                            $pi = $results['profitability_index'] ?? $financialAnalysis->calculateProfitabilityIndex() ?? 0;
                        @endphp
                        <p class="text-3xl font-bold {{ $pi >= 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($pi, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            {{ $pi >= 1 ? '✓ Proyecto viable' : '✗ Proyecto no viable' }}
                        </p>
                        @if($pi > 0)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded text-left text-xs">
                            <p class="font-semibold mb-1">Interpretación:</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                PI > 1: El proyecto genera ${{ number_format($pi, 2) }} por cada $1 invertido.
                            </p>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center p-6">
                        <p class="text-gray-500 dark:text-gray-400 mb-4">No se puede calcular el resultado</p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-left text-sm">
                            <p class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">⚠ Datos faltantes:</p>
                            <ul class="list-disc list-inside text-yellow-700 dark:text-yellow-400 space-y-1">
                                @if(in_array($financialAnalysis->analysis_type, ['van', 'tir', 'payback', 'profitability_index']))
                                    @if(!$financialAnalysis->initial_investment || $financialAnalysis->initial_investment == 0)
                                        <li>Falta la inversión inicial</li>
                                    @endif
                                    @if(empty($financialAnalysis->cash_flows) || !is_array($financialAnalysis->cash_flows) || count($financialAnalysis->cash_flows) == 0)
                                        <li>Faltan los flujos de efectivo</li>
                                    @endif
                                    @if(in_array($financialAnalysis->analysis_type, ['van', 'profitability_index']) && (!$financialAnalysis->discount_rate || $financialAnalysis->discount_rate == 0))
                                        <li>Falta la tasa de descuento</li>
                                    @endif
                                @elseif($financialAnalysis->analysis_type === 'break_even')
                                    @if(!$financialAnalysis->fixed_costs || $financialAnalysis->fixed_costs == 0)
                                        <li>Faltan los costos fijos</li>
                                    @endif
                                    @if(!$financialAnalysis->variable_cost_per_unit)
                                        <li>Falta el costo variable por unidad</li>
                                    @endif
                                    @if(!$financialAnalysis->selling_price_per_unit || $financialAnalysis->selling_price_per_unit == 0)
                                        <li>Falta el precio de venta por unidad</li>
                                    @endif
                                @else
                                    <li>Tipo de análisis no reconocido: {{ $financialAnalysis->analysis_type ?? 'N/A' }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Margen de Contribución (solo para break even) -->
            @if($financialAnalysis->analysis_type === 'break_even')
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Análisis de Contribución</h3>
                @php
                    $sellingPrice = $financialAnalysis->selling_price_per_unit ?? 0;
                    $variableCost = $financialAnalysis->variable_cost_per_unit ?? 0;
                    $contributionMargin = $sellingPrice - $variableCost;
                    $contributionMarginPercent = $sellingPrice > 0 
                        ? ($contributionMargin / $sellingPrice) * 100 
                        : 0;
                @endphp
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Margen de Contribución Unitario</p>
                        <p class="text-2xl font-bold text-green-600">
                            ${{ number_format($contributionMargin, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Porcentaje de Contribución</p>
                        <p class="text-xl font-semibold text-blue-600">
                            {{ number_format($contributionMarginPercent, 2) }}%
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            del precio de venta
                        </p>
                    </div>
                    @if($contributionMargin <= 0)
                    <div class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 rounded text-xs">
                        <p class="text-red-800 dark:text-red-300">
                            ⚠ El precio de venta es menor o igual al costo variable. El negocio no es rentable.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

