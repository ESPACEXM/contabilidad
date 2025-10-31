@extends('layouts.app')

@section('title', 'Balance General')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Balance General</h1>
        <form method="GET" class="flex gap-2">
            <input type="date" name="date" value="{{ $endDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Consultar</button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h2>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-lg mb-4">ACTIVO</h3>
                <div class="space-y-2">
                    @foreach($balanceSheet['assets']['accounts'] ?? [] as $account)
                    <div class="flex justify-between">
                        <span>{{ $account['name'] }}</span>
                        <span>${{ number_format($account['balance'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="border-t pt-2 font-bold flex justify-between">
                        <span>Total Activo</span>
                        <span>${{ number_format($balanceSheet['total_assets'], 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-lg mb-4">PASIVO Y CAPITAL</h3>
                <div class="space-y-2">
                    @foreach($balanceSheet['liabilities']['accounts'] ?? [] as $account)
                    <div class="flex justify-between">
                        <span>{{ $account['name'] }}</span>
                        <span>${{ number_format($account['balance'], 2) }}</span>
                    </div>
                    @endforeach
                    @foreach($balanceSheet['equity']['accounts'] ?? [] as $account)
                    <div class="flex justify-between">
                        <span>{{ $account['name'] }}</span>
                        <span>${{ number_format($account['balance'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="border-t pt-2 font-bold flex justify-between">
                        <span>Total Pasivo + Capital</span>
                        <span>${{ number_format($balanceSheet['total_liabilities'] + $balanceSheet['total_equity'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

