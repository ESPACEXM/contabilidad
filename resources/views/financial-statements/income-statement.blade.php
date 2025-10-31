@extends('layouts.app')

@section('title', 'Estado de Resultados')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Estado de Resultados</h1>
        <form method="GET" class="flex gap-2">
            <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300">
            <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Consultar</button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">
            Del {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </h2>
        
        <div class="space-y-4">
            <div>
                <h3 class="font-bold text-lg mb-2">INGRESOS</h3>
                @foreach($incomeStatement['revenues']['accounts'] ?? [] as $account)
                <div class="flex justify-between pl-4">
                    <span>{{ $account['name'] }}</span>
                    <span>${{ number_format($account['balance'], 2) }}</span>
                </div>
                @endforeach
                <div class="border-t pt-2 font-bold flex justify-between mt-2">
                    <span>Total Ingresos</span>
                    <span>${{ number_format($incomeStatement['total_revenues'], 2) }}</span>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-lg mb-2">EGRESOS</h3>
                @foreach($incomeStatement['expenses']['accounts'] ?? [] as $account)
                <div class="flex justify-between pl-4">
                    <span>{{ $account['name'] }}</span>
                    <span>${{ number_format($account['balance'], 2) }}</span>
                </div>
                @endforeach
                <div class="border-t pt-2 font-bold flex justify-between mt-2">
                    <span>Total Egresos</span>
                    <span>${{ number_format($incomeStatement['total_expenses'], 2) }}</span>
                </div>
            </div>
            
            <div class="border-t-2 pt-4">
                <div class="flex justify-between text-xl font-bold">
                    <span>UTILIDAD NETA</span>
                    <span class="{{ $incomeStatement['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($incomeStatement['net_income'], 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

