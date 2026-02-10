@extends('layouts.app')

@section('title', 'Ver Presupuesto')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('budgets.index') }}" class="text-gray-500 hover:text-gray-700">Presupuestos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">{{ $budget->name }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $budget->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs font-semibold
                    {{ $budget->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                       ($budget->status === 'closed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                    {{ ucfirst($budget->status) }}
                </span>
            </p>
        </div>
        <div class="flex space-x-2">
            @if($budget->status === 'draft')
                <a href="{{ route('budgets.edit', $budget) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Editar
                </a>
                <form action="{{ route('budgets.approve', $budget) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        Aprobar
                    </button>
                </form>
            @endif
            <a href="{{ route('budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Periodo Financiero</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $budget->financialPeriod->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $budget->financialPeriod->start_date->format('d/m/Y') }} - {{ $budget->financialPeriod->end_date->format('d/m/Y') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tipo de Presupuesto</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($budget->budget_type) }}</p>
            </div>
            @if($budget->department)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Departamento</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $budget->department }}</p>
            </div>
            @endif
            @if($budget->cost_center)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Centro de Costo</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $budget->cost_center }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Creado por</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $budget->creator->name ?? 'N/A' }}</p>
            </div>
            @if($budget->approver)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Aprobado por</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $budget->approver->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $budget->approved_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>

        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Partidas del Presupuesto</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cuenta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Mes</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Presupuestado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ejecutado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Variación</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($budget->items as $item)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($item->chartAccount)
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->chartAccount->code }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->chartAccount->name }}</div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $item->description }}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-900 dark:text-white">
                            {{ $item->month ? DateTime::createFromFormat('!m', $item->month)->format('M') : 'Todos' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                            ${{ number_format($item->budgeted_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                            ${{ number_format($item->executed_amount ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                            @php
                                $variance = ($item->budgeted_amount ?? 0) - ($item->executed_amount ?? 0);
                                $variancePercent = $item->budgeted_amount > 0 ? ($variance / $item->budgeted_amount) * 100 : 0;
                            @endphp
                            <span class="{{ $variance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                ${{ number_format($variance, 2) }} ({{ number_format($variancePercent, 1) }}%)
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                        <td colspan="3" class="px-4 py-3 text-right">TOTALES</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">${{ number_format($budget->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">${{ number_format($budget->total_executed ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                            @php
                                $totalVariance = $budget->total_amount - ($budget->total_executed ?? 0);
                            @endphp
                            ${{ number_format($totalVariance, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($budget->notes)
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Notas</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $budget->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection





