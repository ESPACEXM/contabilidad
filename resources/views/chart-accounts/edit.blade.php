@extends('layouts.app')

@section('title', 'Editar Cuenta Contable')

@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('chart-accounts.index') }}" class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 md:ml-2">Catálogo de Cuentas</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Editar</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Cuenta Contable</h1>

        <form action="{{ route('chart-accounts.update', $chartAccount) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código *</label>
                    <input type="text" id="code" name="code" value="{{ old('code', $chartAccount->code) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $chartAccount->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Padre</label>
                    <select id="parent_id" name="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Ninguna (Cuenta raíz)</option>
                        @foreach($rootAccounts as $account)
                            <option value="{{ $account->id }}" {{ old('parent_id', $chartAccount->parent_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="activo" {{ old('type', $chartAccount->type) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="pasivo" {{ old('type', $chartAccount->type) == 'pasivo' ? 'selected' : '' }}>Pasivo</option>
                        <option value="capital" {{ old('type', $chartAccount->type) == 'capital' ? 'selected' : '' }}>Capital</option>
                        <option value="ingreso" {{ old('type', $chartAccount->type) == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                        <option value="egreso" {{ old('type', $chartAccount->type) == 'egreso' ? 'selected' : '' }}>Egreso</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nature" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Naturaleza *</label>
                    <select id="nature" name="nature" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="deudora" {{ old('nature', $chartAccount->nature) == 'deudora' ? 'selected' : '' }}>Deudora</option>
                        <option value="acreedora" {{ old('nature', $chartAccount->nature) == 'acreedora' ? 'selected' : '' }}>Acreedora</option>
                    </select>
                    @error('nature')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nivel *</label>
                    <select id="level" name="level" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="cuenta_mayor" {{ old('level', $chartAccount->level) == 'cuenta_mayor' ? 'selected' : '' }}>Cuenta Mayor</option>
                        <option value="subcuenta" {{ old('level', $chartAccount->level) == 'subcuenta' ? 'selected' : '' }}>Subcuenta</option>
                        <option value="auxiliar" {{ old('level', $chartAccount->level) == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                    </select>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $chartAccount->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="initial_balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo Inicial</label>
                    <input type="number" id="initial_balance" name="initial_balance" value="{{ old('initial_balance', $chartAccount->initial_balance) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('initial_balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orden</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $chartAccount->order) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('order')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="allows_movements" value="1" {{ old('allows_movements', $chartAccount->allows_movements) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permitir movimientos</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $chartAccount->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Activa</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('chart-accounts.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Actualizar Cuenta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

