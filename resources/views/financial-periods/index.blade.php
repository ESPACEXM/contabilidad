@extends('layouts.app')

@section('title', 'Periodos Financieros')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Periodos Financieros</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Periodos Financieros</h1>
        <a href="{{ route('financial-periods.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Periodo
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Periodo</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Presupuestos</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($periods as $period)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $period->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ ucfirst($period->period_type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-white">
                            {{ $period->budgets_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($period->is_closed)
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    Cerrado
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Abierto
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('financial-periods.show', $period) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                            @if(!$period->is_closed)
                                <a href="{{ route('financial-periods.edit', $period) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="{{ route('financial-periods.close', $period) }}" method="POST" class="inline mr-3">
                                    @csrf
                                    <button type="submit" class="text-orange-600 hover:text-orange-900" onclick="return confirm('¿Cerrar este periodo?')">Cerrar</button>
                                </form>
                                <form action="{{ route('financial-periods.destroy', $period) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay periodos financieros registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($periods->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $periods->links() }}
        </div>
        @endif
    </div>
</div>
@endsection




