@extends('layouts.app')

@section('title', 'Crear Análisis Financiero')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('financial-analysis.index') }}" class="text-gray-500 hover:text-gray-700">Análisis Financiero</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Crear</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Análisis Financiero</h1>
        <a href="{{ route('financial-analysis.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
            Volver
        </a>
    </div>

    <form action="{{ route('financial-analysis.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre del Análisis *
                </label>
                <input type="text" name="name" id="name" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="analysis_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipo de Análisis *
                </label>
                <select name="analysis_type" id="analysis_type" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione un tipo</option>
                    <option value="van" {{ old('analysis_type') == 'van' || request('type') == 'van' ? 'selected' : '' }}>VAN - Valor Actual Neto</option>
                    <option value="tir" {{ old('analysis_type') == 'tir' || request('type') == 'tir' ? 'selected' : '' }}>TIR - Tasa Interna de Retorno</option>
                    <option value="break_even" {{ old('analysis_type') == 'break_even' || request('type') == 'break_even' ? 'selected' : '' }}>Punto de Equilibrio</option>
                    <option value="payback" {{ old('analysis_type') == 'payback' ? 'selected' : '' }}>Periodo de Recuperación</option>
                    <option value="profitability_index" {{ old('analysis_type') == 'profitability_index' ? 'selected' : '' }}>Índice de Rentabilidad</option>
                </select>
                @error('analysis_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Campos para VAN y TIR -->
        <div id="investment-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
            <div>
                <label for="initial_investment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Inversión Inicial *
                </label>
                <input type="number" name="initial_investment" id="initial_investment" step="0.01" min="0"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('initial_investment', 0) }}">
                @error('initial_investment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discount_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tasa de Descuento (%)
                </label>
                <input type="number" name="discount_rate" id="discount_rate" step="0.01" min="0" max="100"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('discount_rate', 0) }}">
                @error('discount_rate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Campos para Punto de Equilibrio -->
        <div id="break-even-fields" class="grid grid-cols-1 md:grid-cols-3 gap-6" style="display: none;">
            <div>
                <label for="fixed_costs" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Costos Fijos *
                </label>
                <input type="number" name="fixed_costs" id="fixed_costs" step="0.01" min="0"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('fixed_costs', 0) }}">
            </div>

            <div>
                <label for="variable_cost_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Costo Variable por Unidad *
                </label>
                <input type="number" name="variable_cost_per_unit" id="variable_cost_per_unit" step="0.01" min="0"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('variable_cost_per_unit', 0) }}">
            </div>

            <div>
                <label for="selling_price_per_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Precio de Venta por Unidad *
                </label>
                <input type="number" name="selling_price_per_unit" id="selling_price_per_unit" step="0.01" min="0"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('selling_price_per_unit', 0) }}">
            </div>
        </div>

        <!-- Flujos de efectivo para VAN, TIR, Payback -->
        <div id="cash-flows-field" style="display: none;">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Flujos de Efectivo (separados por comas) *
            </label>
            <textarea name="cash_flows_input" id="cash_flows_input" rows="3" placeholder="Ejemplo: 5000, 6000, 7000, 8000"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Ingrese los flujos de efectivo separados por comas. El primer flujo corresponde al período 1.
            </p>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Notas
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t dark:border-gray-700">
            <a href="{{ route('financial-analysis.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Crear Análisis
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const analysisType = document.getElementById('analysis_type');
    const investmentFields = document.getElementById('investment-fields');
    const breakEvenFields = document.getElementById('break-even-fields');
    const cashFlowsField = document.getElementById('cash-flows-field');
    const initialInvestment = document.getElementById('initial_investment');
    const discountRate = document.getElementById('discount_rate');

    function toggleFields() {
        const type = analysisType.value;
        
        // Ocultar todos los campos
        investmentFields.style.display = 'none';
        breakEvenFields.style.display = 'none';
        cashFlowsField.style.display = 'none';
        
        // Mostrar campos según el tipo
        if (['van', 'tir'].includes(type)) {
            investmentFields.style.display = 'grid';
            cashFlowsField.style.display = 'block';
            initialInvestment.required = true;
            document.getElementById('cash_flows_input').required = true;
            if (type === 'van') {
                discountRate.required = true;
            } else {
                discountRate.required = false;
            }
            // Limpiar campos de break even
            document.getElementById('fixed_costs').required = false;
            document.getElementById('variable_cost_per_unit').required = false;
            document.getElementById('selling_price_per_unit').required = false;
        } else if (type === 'break_even') {
            breakEvenFields.style.display = 'grid';
            document.getElementById('fixed_costs').required = true;
            document.getElementById('variable_cost_per_unit').required = true;
            document.getElementById('selling_price_per_unit').required = true;
            // Ocultar y limpiar campos de inversión
            investmentFields.style.display = 'none';
            cashFlowsField.style.display = 'none';
            initialInvestment.required = false;
            discountRate.required = false;
            document.getElementById('cash_flows_input').required = false;
        } else {
            // Ocultar todos los campos si no hay tipo seleccionado
            investmentFields.style.display = 'none';
            breakEvenFields.style.display = 'none';
            cashFlowsField.style.display = 'none';
        }
    }

    analysisType.addEventListener('change', toggleFields);
    
    // Inicializar campos si hay un tipo preseleccionado
    if (analysisType.value) {
        toggleFields();
    }
    
    // Procesar flujos de efectivo antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        const analysisType = document.getElementById('analysis_type').value;
        const cashFlowsInput = document.getElementById('cash_flows_input');
        
        // Validar que los campos requeridos estén llenos
        if (['van', 'tir'].includes(analysisType)) {
            if (!cashFlowsInput || !cashFlowsInput.value.trim()) {
                e.preventDefault();
                alert('Por favor, ingrese los flujos de efectivo separados por comas.');
                cashFlowsInput.focus();
                return false;
            }
            
            const flows = cashFlowsInput.value.split(',').map(f => parseFloat(f.trim())).filter(f => !isNaN(f));
            if (flows.length === 0) {
                e.preventDefault();
                alert('Por favor, ingrese al menos un flujo de efectivo válido.');
                cashFlowsInput.focus();
                return false;
            }
            
            // Crear campo oculto con los flujos procesados
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'cash_flows_input';
            hiddenInput.value = cashFlowsInput.value.trim();
            this.appendChild(hiddenInput);
        }
    });
});
</script>
@endsection





