@extends('layouts.app')

@section('title', 'Estado de Resultados')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Estado de Resultados</h1>
        <form method="GET" class="flex gap-2">
            <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Consultar</button>
        </form>
    </div>

    <!-- Gráficas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Gráfica de Ingresos vs Egresos -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Ingresos vs Egresos</h3>
            <canvas id="incomeExpenseChart" height="300"></canvas>
        </div>
        
        <!-- Gráfica de Distribución de Egresos -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Distribución de Egresos</h3>
            <canvas id="expensesChart" height="300"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
            Del {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </h2>
        
        <div class="space-y-4">
            <div>
                <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">INGRESOS</h3>
                @foreach($incomeStatement['revenues']['accounts'] ?? [] as $account)
                <div class="flex justify-between pl-4 text-gray-900 dark:text-white">
                    <span>{{ $account['name'] }}</span>
                    <span>${{ number_format($account['balance'], 2) }}</span>
                </div>
                @endforeach
                <div class="border-t pt-2 font-bold flex justify-between mt-2 text-gray-900 dark:text-white">
                    <span>Total Ingresos</span>
                    <span>${{ number_format($incomeStatement['total_revenues'], 2) }}</span>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">EGRESOS</h3>
                @foreach($incomeStatement['expenses']['accounts'] ?? [] as $account)
                <div class="flex justify-between pl-4 text-gray-900 dark:text-white">
                    <span>{{ $account['name'] }}</span>
                    <span>${{ number_format($account['balance'], 2) }}</span>
                </div>
                @endforeach
                <div class="border-t pt-2 font-bold flex justify-between mt-2 text-gray-900 dark:text-white">
                    <span>Total Egresos</span>
                    <span>${{ number_format($incomeStatement['total_expenses'], 2) }}</span>
                </div>
            </div>
            
            <div class="border-t-2 pt-4">
                <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-white">
                    <span>UTILIDAD NETA</span>
                    <span class="{{ $incomeStatement['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($incomeStatement['net_income'], 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Datos para gráficas
    const incomeExpenseData = {
        labels: ['Ingresos', 'Egresos'],
        values: [
            {{ $incomeStatement['total_revenues'] ?? 0 }},
            {{ abs($incomeStatement['total_expenses'] ?? 0) }}
        ]
    };

    const expensesData = {
        labels: [
            @foreach($incomeStatement['expenses']['accounts'] ?? [] as $account)
                '{{ $account['name'] }}',
            @endforeach
        ],
        values: [
            @foreach($incomeStatement['expenses']['accounts'] ?? [] as $account)
                {{ abs($account['balance']) }},
            @endforeach
        ]
    };

    // Configuración de colores
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#f3f4f6' : '#1f2937';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    // Gráfica de Ingresos vs Egresos
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
    if (incomeExpenseCtx) {
        new Chart(incomeExpenseCtx, {
            type: 'bar',
            data: {
                labels: incomeExpenseData.labels,
                datasets: [{
                    label: 'Monto',
                    data: incomeExpenseData.values,
                    backgroundColor: ['#10b981', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-MX');
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Distribución de Egresos
    const expensesCtx = document.getElementById('expensesChart');
    if (expensesCtx && expensesData.values.length > 0) {
        new Chart(expensesCtx, {
            type: 'doughnut',
            data: {
                labels: expensesData.labels,
                datasets: [{
                    data: expensesData.values,
                    backgroundColor: [
                        '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6',
                        '#06b6d4', '#ec4899', '#84cc16', '#f97316', '#6366f1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(2) : 0;
                                return label + ': $' + value.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection

