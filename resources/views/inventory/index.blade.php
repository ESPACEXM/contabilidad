@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li class="flex items-center"><span class="mx-2">/</span><span class="text-gray-700">Inventario</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Movimientos de Inventario</h1>
        <a href="{{ route('inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Movimiento
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="product_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="">Todos los productos</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            <select name="type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="">Todos los tipos</option>
                <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Salida</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <div class="col-span-4 flex gap-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Filtrar</button>
                <a href="{{ route('inventory.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo Unit.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($movements as $movement)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $movement->movement_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $movement->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ ucfirst($movement->type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ number_format($movement->quantity, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ${{ number_format($movement->unit_cost, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($movement->stock_before, 2) }} → {{ number_format($movement->stock_after, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection

