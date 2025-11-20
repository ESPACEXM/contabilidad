@extends('layouts.app')

@section('title', 'Ver Póliza Contable')

@section('breadcrumb')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('journal-entries.index') }}" class="text-gray-500 hover:text-gray-700">Pólizas Contables</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Póliza #{{ $journalEntry->entry_number }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Póliza #{{ $journalEntry->entry_number }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs font-semibold
                    {{ $journalEntry->status === 'posted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                    {{ $journalEntry->status === 'posted' ? 'Contabilizada' : 'Borrador' }}
                </span>
            </p>
        </div>
        <div class="flex space-x-2">
            @if($journalEntry->status === 'draft')
                <a href="{{ route('journal-entries.edit', $journalEntry) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Editar
                </a>
                <form action="{{ route('journal-entries.post', $journalEntry) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        Contabilizar
                    </button>
                </form>
            @endif
            <a href="{{ route('journal-entries.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->entry_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tipo</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($journalEntry->type) }}</p>
            </div>
            @if($journalEntry->reference)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Referencia</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->reference }}</p>
            </div>
            @endif
            @if($journalEntry->description)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">Descripción</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->description }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Creado por</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->creator->name ?? 'N/A' }}</p>
            </div>
            @if($journalEntry->approver)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Aprobado por</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->approver->name }}</p>
            </div>
            @endif
        </div>

        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Partidas</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cuenta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Debe</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Haber</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($journalEntry->items as $item)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->chartAccount->code }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->chartAccount->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $item->description ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            @if($item->type === 'debit')
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item->amount, 2) }}</span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            @if($item->type === 'credit')
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item->amount, 2) }}</span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                        <td colspan="2" class="px-4 py-3 text-right">TOTALES</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">${{ number_format($journalEntry->total_debit, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">${{ number_format($journalEntry->total_credit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">Diferencia:</span>
                <span class="text-lg font-bold {{ abs($journalEntry->total_debit - $journalEntry->total_credit) == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    ${{ number_format(abs($journalEntry->total_debit - $journalEntry->total_credit), 2) }}
                </span>
            </div>
            @if($journalEntry->isBalanced())
            <p class="text-sm text-green-600 dark:text-green-400 mt-2">✓ La póliza está balanceada</p>
            @else
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">⚠ La póliza no está balanceada</p>
            @endif
        </div>
    </div>
</div>
@endsection



