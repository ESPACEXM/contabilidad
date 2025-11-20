@extends('layouts.app')

@section('title', 'Ver Producto')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Productos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $product->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($product->sku) SKU: {{ $product->sku }} | @endif
                {{ ucfirst($product->type) }}
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Editar
            </a>
            <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información General</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Categoría</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->category->name ?? 'Sin categoría' }}</p>
                </div>
                @if($product->sku)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">SKU</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->sku }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tipo</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($product->type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unidad de Medida</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->unit_of_measure }}</p>
                </div>
                @if($product->description)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Descripción</p>
                    <p class="text-gray-900 dark:text-white">{{ $product->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información Financiera</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Precio Unitario</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($product->unit_price, 2) }}</p>
                </div>
                @if($product->cost_price)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Costo</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($product->cost_price, 2) }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Método de Valuación</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ strtoupper($product->valuation_method) }}</p>
                </div>
            </div>
        </div>

        @if($product->track_inventory)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Inventario</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Actual</p>
                    <p class="text-2xl font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                        {{ number_format($product->stock_quantity, 2) }} {{ $product->unit_of_measure }}
                    </p>
                    @if($product->isLowStock())
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">⚠ Stock bajo</p>
                    @endif
                </div>
                @if($product->min_stock)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Mínimo</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($product->min_stock, 2) }} {{ $product->unit_of_measure }}</p>
                </div>
                @endif
                @if($product->max_stock)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Máximo</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($product->max_stock, 2) }} {{ $product->unit_of_measure }}</p>
                </div>
                @endif
                <div class="mt-4">
                    <a href="{{ route('inventory.kardex', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                        Ver Kardex
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($product->movements->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Últimos Movimientos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo Unitario</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($product->movements->take(10) as $movement)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $movement->movement_date->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ ucfirst($movement->type) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            {{ number_format($movement->quantity, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            ${{ number_format($movement->unit_cost, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            ${{ number_format($movement->total_cost, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection



