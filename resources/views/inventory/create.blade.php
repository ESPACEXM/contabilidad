@extends('layouts.app')

@section('title', 'Nuevo Movimiento de Inventario')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('inventory.index') }}" class="text-gray-500 hover:text-gray-700">Movimientos de Inventario</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Nuevo Movimiento</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Nuevo Movimiento de Inventario</h1>

        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Producto *</label>
                    <select id="product_id" name="product_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Seleccionar producto...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Stock: {{ number_format($product->stock_quantity, 2) }} {{ $product->unit_of_measure }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="movement_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha *</label>
                    <input type="date" id="movement_date" name="movement_date" value="{{ old('movement_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('movement_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Movimiento *</label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="entry" {{ old('type') === 'entry' ? 'selected' : '' }}>Entrada</option>
                        <option value="exit" {{ old('type') === 'exit' ? 'selected' : '' }}>Salida</option>
                        <option value="adjustment" {{ old('type') === 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                        <option value="transfer" {{ old('type') === 'transfer' ? 'selected' : '' }}>Transferencia</option>
                    </select>
                    @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad *</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" step="0.001" min="0.001" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Costo Unitario</label>
                    <input type="number" id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                           placeholder="Se usará el costo del producto si se deja vacío">
                    @error('unit_cost')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referencia</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('reference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('inventory.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection




