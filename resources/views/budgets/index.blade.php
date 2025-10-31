@extends('layouts.app')

@section('title', 'Presupuestos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Presupuestos</h1>
        <a href="{{ route('budgets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Presupuesto
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Periodo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Presupuestado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ejecutado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Variación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($budgets as $budget)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $budget->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $budget->financialPeriod->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ${{ number_format($budget->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ${{ number_format($budget->executed_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="{{ $budget->variance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($budget->variance_percentage, 2) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $budget->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $budget->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            @if($budget->status === 'draft')
                                <a href="{{ route('budgets.edit', $budget) }}" class="ml-4 text-indigo-600">Editar</a>
                                <form action="{{ route('budgets.approve', $budget) }}" method="POST" class="inline ml-4">
                                    @csrf
                                    <button type="submit" class="text-green-600">Aprobar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay presupuestos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">{{ $budgets->links() }}</div>
    </div>
</div>
@endsection

