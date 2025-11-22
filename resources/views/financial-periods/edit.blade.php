@extends('layouts.app')

@section('title', 'Editar Periodo Financiero')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('financial-periods.index') }}" class="text-gray-500 hover:text-gray-700">Periodos Financieros</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Editar</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Periodo Financiero</h1>

        <form action="{{ route('financial-periods.update', $financialPeriod) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $financialPeriod->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="period_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Periodo *</label>
                    <select id="period_type" name="period_type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="monthly" {{ old('period_type', $financialPeriod->period_type) === 'monthly' ? 'selected' : '' }}>Mensual</option>
                        <option value="quarterly" {{ old('period_type', $financialPeriod->period_type) === 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                        <option value="yearly" {{ old('period_type', $financialPeriod->period_type) === 'yearly' ? 'selected' : '' }}>Anual</option>
                    </select>
                    @error('period_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Inicio *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $financialPeriod->start_date->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Fin *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $financialPeriod->end_date->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('financial-periods.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Actualizar Periodo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection




