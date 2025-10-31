@extends('layouts.app')

@section('title', 'Análisis Financiero')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Análisis Financiero</h1>
        <a href="{{ route('financial-analysis.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Análisis
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('financial-analysis.create') }}?type=van" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">VAN</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Valor Actual Neto</p>
        </a>
        <a href="{{ route('financial-analysis.create') }}?type=tir" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">TIR</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Tasa Interna de Retorno</p>
        </a>
        <a href="{{ route('financial-analysis.create') }}?type=break_even" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">Punto de Equilibrio</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Análisis de punto de equilibrio</p>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Resultado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($analyses as $analysis)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        {{ $analysis->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ strtoupper($analysis->analysis_type) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        ${{ number_format($analysis->result_value ?? 0, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('financial-analysis.show', $analysis) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay análisis registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $analyses->links() }}</div>
    </div>
</div>
@endsection

