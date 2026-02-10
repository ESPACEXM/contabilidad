@extends('layouts.app')

@section('title', 'Editar Presupuesto')

@section('breadcrumb')
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('budgets.index') }}" class="text-gray-500 hover:text-gray-700">Presupuestos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Editar</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Presupuesto</h1>

        <form action="{{ route('budgets.update', $budget) }}" method="POST" id="budgetForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="financial_period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periodo Financiero *</label>
                    <select id="financial_period_id" name="financial_period_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ old('financial_period_id', $budget->financial_period_id) == $period->id ? 'selected' : '' }}>
                                {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('financial_period_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $budget->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="budget_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Presupuesto *</label>
                    <select id="budget_type" name="budget_type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="operational" {{ old('budget_type', $budget->budget_type) === 'operational' ? 'selected' : '' }}>Operacional</option>
                        <option value="capital" {{ old('budget_type', $budget->budget_type) === 'capital' ? 'selected' : '' }}>Capital</option>
                        <option value="departmental" {{ old('budget_type', $budget->budget_type) === 'departmental' ? 'selected' : '' }}>Departamental</option>
                    </select>
                    @error('budget_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departamento</label>
                    <input type="text" id="department" name="department" value="{{ old('department', $budget->department) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="cost_center" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Centro de Costo</label>
                    <input type="text" id="cost_center" name="cost_center" value="{{ old('cost_center', $budget->cost_center) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @error('cost_center')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">{{ old('notes', $budget->notes) }}</textarea>
                    @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Partidas del Presupuesto</h2>
                    <button type="button" onclick="addItem()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                        + Agregar Partida
                    </button>
                </div>

                <div id="items-container" class="space-y-4">
                    @foreach($budget->items as $index => $item)
                    <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable</label>
                                <select name="items[{{ $index }}][chart_account_id]"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <option value="">Sin cuenta</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $item->chart_account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->code }} - {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción *</label>
                                <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto *</label>
                                <input type="number" name="items[{{ $index }}][budgeted_amount]" value="{{ $item->budgeted_amount }}" step="0.01" min="0.01" required onchange="updateTotal()" oninput="updateTotal()"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mes</label>
                                <select name="items[{{ $index }}][month]"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <option value="">Todos</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $item->month == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button type="button" onclick="removeItem(this)" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-3 rounded-lg">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Presupuestado</p>
                    <p id="total-amount" class="text-2xl font-bold text-gray-900 dark:text-white">$0.00</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Actualizar Presupuesto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = {{ $budget->items->count() }};
const accounts = @json($accounts);

function addItem() {
    const container = document.getElementById('items-container');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border border-gray-300 dark:border-gray-700 rounded-lg p-4';
    itemDiv.innerHTML = `
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable</label>
                <select name="items[${itemIndex}][chart_account_id]"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Sin cuenta</option>
                    ${accounts.map(acc => `<option value="${acc.id}">${acc.code} - ${acc.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción *</label>
                <input type="text" name="items[${itemIndex}][description]" required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto *</label>
                <input type="number" name="items[${itemIndex}][budgeted_amount]" step="0.01" min="0.01" required onchange="updateTotal()" oninput="updateTotal()"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mes</label>
                <select name="items[${itemIndex}][month]"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Todos</option>
                    <option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option>
                    <option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option>
                    <option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option>
                    <option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="button" onclick="removeItem(this)" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-3 rounded-lg">
                    ×
                </button>
            </div>
        </div>
    `;
    container.appendChild(itemDiv);
    itemIndex++;
    updateTotal();
}

function removeItem(button) {
    button.closest('div[class*="border"]').remove();
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('[name*="[budgeted_amount]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
}

updateTotal();
</script>
@endsection





