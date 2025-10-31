@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Dashboard
            </a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Bienvenido, {{ $user->name }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Empresa: {{ $tenant->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cuentas</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $kpis['total_accounts'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pólizas Contabilizadas</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $kpis['posted_entries'] }}/{{ $kpis['journal_entries'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Productos</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $kpis['total_products'] }}</p>
                    @if($kpis['low_stock_products'] > 0)
                        <p class="text-xs text-red-600 mt-1">{{ $kpis['low_stock_products'] }} con stock bajo</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Presupuestos Activos</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $kpis['active_budgets'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance General Resumen -->
    @if(isset($balanceSheet) && isset($incomeStatement))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Resumen Financiero</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Activos</span>
                    <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($balanceSheet['total_assets'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Pasivos</span>
                    <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($balanceSheet['total_liabilities'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Capital</span>
                    <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($balanceSheet['total_equity'] ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Estado de Resultados</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Ingresos</span>
                    <span class="font-semibold text-green-600">${{ number_format($incomeStatement['total_revenues'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Egresos</span>
                    <span class="font-semibold text-red-600">${{ number_format($incomeStatement['total_expenses'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-900 dark:text-white font-bold">Utilidad Neta</span>
                    <span class="font-bold {{ ($incomeStatement['net_income'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($incomeStatement['net_income'] ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pólizas y Movimientos Recientes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pólizas Recientes</h2>
            <div class="space-y-3">
                @forelse($recentEntries as $entry)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $entry->entry_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $entry->entry_date->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded {{ $entry->status === 'posted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $entry->status === 'posted' ? 'Contabilizada' : 'Borrador' }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">No hay pólizas recientes</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Movimientos de Inventario</h2>
            <div class="space-y-3">
                @forelse($recentMovements as $movement)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $movement->product->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $movement->movement_date->format('d/m/Y') }} - {{ ucfirst($movement->type) }}</p>
                    </div>
                    <span class="text-sm text-gray-900 dark:text-white">{{ number_format($movement->quantity, 2) }}</span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">No hay movimientos recientes</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('journal-entries.create') }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-8 h-8 text-blue-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Nueva Póliza</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Crear póliza contable</p>
                </div>
            </a>
            <a href="{{ route('products.create') }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-8 h-8 text-green-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Nuevo Producto</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Agregar producto</p>
                </div>
            </a>
            <a href="{{ route('financial-statements.balance-sheet') }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-8 h-8 text-purple-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Estados Financieros</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ver reportes</p>
                </div>
            </a>
            <a href="{{ route('budgets.create') }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-8 h-8 text-yellow-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Nuevo Presupuesto</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Crear presupuesto</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

