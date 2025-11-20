@extends('layouts.app')

@section('title', 'Editar Producto')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Productos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Editar</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Producto</h1>

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('sku')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                    <select id="category_id" name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="product" {{ old('type', $product->type) === 'product' ? 'selected' : '' }}>Producto</option>
                        <option value="service" {{ old('type', $product->type) === 'service' ? 'selected' : '' }}>Servicio</option>
                    </select>
                    @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio Unitario *</label>
                    <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price', $product->unit_price) }}" step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('unit_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="cost_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Costo</label>
                    <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('cost_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidad de Medida *</label>
                    <input type="text" id="unit_of_measure" name="unit_of_measure" value="{{ old('unit_of_measure', $product->unit_of_measure) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('unit_of_measure')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="valuation_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Valuación *</label>
                    <select id="valuation_method" name="valuation_method" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="fifo" {{ old('valuation_method', $product->valuation_method) === 'fifo' ? 'selected' : '' }}>FIFO</option>
                        <option value="lifo" {{ old('valuation_method', $product->valuation_method) === 'lifo' ? 'selected' : '' }}>LIFO</option>
                        <option value="average" {{ old('valuation_method', $product->valuation_method) === 'average' ? 'selected' : '' }}>Promedio</option>
                    </select>
                    @error('valuation_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Mínimo</label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" step="0.001" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('min_stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="max_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Máximo</label>
                    <input type="number" id="max_stock" name="max_stock" value="{{ old('max_stock', $product->max_stock) }}" step="0.001" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('max_stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}
                               class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Controlar inventario</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



