@extends('layouts.app')

@section('title', 'Ver Categoría de Producto')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('product-categories.index') }}" class="text-gray-500 hover:text-gray-700">Categorías</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $productCategory->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $productCategory->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $productCategory->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    {{ $productCategory->is_active ? 'Activa' : 'Inactiva' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('product-categories.edit', $productCategory) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Editar
            </a>
            <a href="{{ route('product-categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información General</h2>
            <div class="space-y-3">
                @if($productCategory->code)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Código</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $productCategory->code }}</p>
                </div>
                @endif
                @if($productCategory->parent)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Categoría Padre</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $productCategory->parent->name }}</p>
                </div>
                @endif
                @if($productCategory->description)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Descripción</p>
                    <p class="text-gray-900 dark:text-white">{{ $productCategory->description }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($productCategory->children->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Subcategorías</h2>
            <ul class="space-y-2">
                @foreach($productCategory->children as $child)
                <li>
                    <a href="{{ route('product-categories.show', $child) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $child->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    @if($productCategory->products->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Productos ({{ $productCategory->products->count() }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SKU</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Precio</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($productCategory->products->take(10) as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $product->sku ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">${{ number_format($product->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">{{ number_format($product->stock_quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection



