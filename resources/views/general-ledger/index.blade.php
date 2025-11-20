@extends('layouts.app')

@section('title', 'Libro Mayor')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="list-none p-0 inline-flex">
        <li class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
            </svg>
        </li>
        <li class="text-gray-700">Libro Mayor</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Libro Mayor</h1>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="GET" action="{{ route('general-ledger.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable</label>
                <select name="account_id" id="account_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccione una cuenta</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->code }} - {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Inicio</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Fin</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Consultar
                </button>
            </div>
        </form>
    </div>

    @if($account)
    <!-- Información de la Cuenta -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Cuenta: {{ $account->code }} - {{ $account->name }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tipo</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($account->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Naturaleza</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($account->nature) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Inicial</p>
                <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($initialBalance, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Periodo</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tabla del Libro Mayor -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Póliza</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Referencia</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Debe</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Haber</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Saldo</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Saldo Inicial -->
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-semibold" colspan="4">
                            Saldo Inicial
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white"></td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white"></td>
                        <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($initialBalance, 2) }}
                        </td>
                    </tr>
                    
                    @forelse($ledger as $entry)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            {{ $entry['date']->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            {{ $entry['entry_number'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            {{ $entry['reference'] ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            {{ $entry['description'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                            {{ $entry['debit'] > 0 ? '$' . number_format($entry['debit'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                            {{ $entry['credit'] > 0 ? '$' . number_format($entry['credit'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($entry['balance'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay movimientos en el período seleccionado
                        </td>
                    </tr>
                    @endforelse
                    
                    @if(count($ledger) > 0)
                    <!-- Totales -->
                    @php
                        $totalDebit = collect($ledger)->sum('debit');
                        $totalCredit = collect($ledger)->sum('credit');
                        $finalBalance = $initialBalance + ($account->nature === 'deudora' ? ($totalDebit - $totalCredit) : ($totalCredit - $totalDebit));
                    @endphp
                    <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" colspan="4">
                            Totales
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                            ${{ number_format($totalDebit, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                            ${{ number_format($totalCredit, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                            ${{ number_format($finalBalance, 2) }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
        <p class="text-gray-500 dark:text-gray-400">Seleccione una cuenta contable para ver el libro mayor</p>
    </div>
    @endif
</div>
@endsection

