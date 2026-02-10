@extends('layouts.app')

@section('title', 'Ver Periodo Financiero')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('financial-periods.index') }}" class="text-gray-500 hover:text-gray-700">Periodos Financieros</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $financialPeriod->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $financialPeriod->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $financialPeriod->is_closed ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                    {{ $financialPeriod->is_closed ? 'Cerrado' : 'Abierto' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-2">
            @if(!$financialPeriod->is_closed)
                <a href="{{ route('financial-periods.edit', $financialPeriod) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Editar
                </a>
                <form action="{{ route('financial-periods.close', $financialPeriod) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg">
                        Cerrar Periodo
                    </button>
                </form>
            @endif
            <a href="{{ route('financial-periods.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información del Periodo</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tipo de Periodo</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($financialPeriod->period_type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Inicio</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $financialPeriod->start_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Fin</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $financialPeriod->end_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Duración</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $financialPeriod->start_date->diffInDays($financialPeriod->end_date) + 1 }} días</p>
                </div>
                @if($financialPeriod->is_closed && $financialPeriod->closer)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cerrado por</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $financialPeriod->closer->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $financialPeriod->closed_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Resumen</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Presupuestos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $financialPeriod->budgets->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($financialPeriod->budgets->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Presupuestos del Periodo</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($financialPeriod->budgets as $budget)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $budget->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ ucfirst($budget->budget_type) }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">${{ number_format($budget->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $budget->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   ($budget->status === 'closed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-medium">
                            <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
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





