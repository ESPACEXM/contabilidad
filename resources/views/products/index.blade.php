@extends('layouts.app')

@section('title', 'Productos')

@section('breadcrumb')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li class="flex items-center"><span class="mx-2">/</span><span class="text-gray-700">Productos</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Productos</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Producto
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-4 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o SKU..." 
                class="flex-1 min-w-[200px] rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <select name="category_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <label class="flex items-center">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} 
                    class="rounded border-gray-300">
                <span class="ml-2 text-sm">Stock bajo</span>
            </label>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Filtrar</button>
            <a href="{{ route('products.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Limpiar</a>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Precio</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($product->track_inventory)
                                <span class="{{ $product->hasLowStock() ? 'text-red-600 font-semibold' : 'text-gray-900 dark:text-white' }}">
                                    {{ number_format($product->stock_quantity, 2) }} {{ $product->unit_of_measure }}
                                </span>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ${{ number_format($product->unit_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Ver</a>
                            <a href="{{ route('products.edit', $product) }}" class="ml-4 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                            @if($product->track_inventory)
                                <a href="{{ route('inventory.kardex', $product) }}" class="ml-4 text-green-600 hover:text-green-900 dark:text-green-400">Kardex</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay productos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

