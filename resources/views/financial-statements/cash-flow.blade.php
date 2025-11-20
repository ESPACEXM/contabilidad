@extends('layouts.app')

@section('title', 'Estado de Flujo de Efectivo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Estado de Flujo de Efectivo</h1>
        <form method="GET" class="flex gap-2">
            <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Consultar</button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">
            Periodo: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </h2>
        
        <div class="space-y-6">
            <!-- Actividades de Operación -->
            <div>
                <h3 class="font-bold text-lg mb-3">Actividades de Operación</h3>
                <div class="space-y-2 ml-4">
                    @foreach($cashFlow['operating']['items'] ?? [] as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['description'] ?? $item['account'] ?? 'N/A' }}</span>
                        <span class="font-semibold {{ ($item['amount'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($item['amount'] ?? 0, 2) }}
                        </span>
                    </div>
                    @endforeach
                    <div class="border-t pt-2 font-bold flex justify-between">
                        <span>Flujo Neto de Operación</span>
                        <span class="{{ ($cashFlow['operating']['total'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($cashFlow['operating']['total'] ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actividades de Inversión -->
            <div>
                <h3 class="font-bold text-lg mb-3">Actividades de Inversión</h3>
                <div class="space-y-2 ml-4">
                    @foreach($cashFlow['investing']['items'] ?? [] as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['description'] ?? $item['account'] ?? 'N/A' }}</span>
                        <span class="font-semibold {{ ($item['amount'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($item['amount'] ?? 0, 2) }}
                        </span>
                    </div>
                    @endforeach
                    <div class="border-t pt-2 font-bold flex justify-between">
                        <span>Flujo Neto de Inversión</span>
                        <span class="{{ ($cashFlow['investing']['total'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($cashFlow['investing']['total'] ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actividades de Financiamiento -->
            <div>
                <h3 class="font-bold text-lg mb-3">Actividades de Financiamiento</h3>
                <div class="space-y-2 ml-4">
                    @foreach($cashFlow['financing']['items'] ?? [] as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['description'] ?? $item['account'] ?? 'N/A' }}</span>
                        <span class="font-semibold {{ ($item['amount'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($item['amount'] ?? 0, 2) }}
                        </span>
                    </div>
                    @endforeach
                    <div class="border-t pt-2 font-bold flex justify-between">
                        <span>Flujo Neto de Financiamiento</span>
                        <span class="{{ ($cashFlow['financing']['total'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($cashFlow['financing']['total'] ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Flujo Neto -->
            <div class="border-t-2 pt-4">
                <div class="flex justify-between text-xl font-bold">
                    <span>Flujo Neto del Periodo</span>
                    <span class="{{ ($cashFlow['net_cash_flow'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($cashFlow['net_cash_flow'] ?? 0, 2) }}
                    </span>
                </div>
            </div>

            <!-- Saldo Inicial y Final -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Inicial de Efectivo</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($cashFlow['beginning_cash'] ?? 0, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Final de Efectivo</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        ${{ number_format($cashFlow['ending_cash'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



