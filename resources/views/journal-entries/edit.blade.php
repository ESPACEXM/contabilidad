@extends('layouts.app')

@section('title', 'Editar Póliza Contable')

@section('breadcrumb')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="{{ route('journal-entries.index') }}" class="text-gray-500 hover:text-gray-700">Pólizas Contables</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Editar Póliza #{{ $journalEntry->entry_number }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Póliza Contable #{{ $journalEntry->entry_number }}</h1>

        <form action="{{ route('journal-entries.update', $journalEntry) }}" method="POST" id="journalEntryForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="entry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha *</label>
                    <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', $journalEntry->entry_date->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('entry_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="diario" {{ old('type', $journalEntry->type) === 'diario' ? 'selected' : '' }}>Diario</option>
                        <option value="ingresos" {{ old('type', $journalEntry->type) === 'ingresos' ? 'selected' : '' }}>Ingresos</option>
                        <option value="egresos" {{ old('type', $journalEntry->type) === 'egresos' ? 'selected' : '' }}>Egresos</option>
                        <option value="apertura" {{ old('type', $journalEntry->type) === 'apertura' ? 'selected' : '' }}>Apertura</option>
                        <option value="cierre" {{ old('type', $journalEntry->type) === 'cierre' ? 'selected' : '' }}>Cierre</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referencia</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference', $journalEntry->reference) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $journalEntry->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Partidas de la Póliza</h2>
                    <button type="button" onclick="addItem()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                        + Agregar Partida
                    </button>
                </div>

                <div id="items-container" class="space-y-4">
                    @foreach($journalEntry->items as $index => $item)
                    <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable *</label>
                                <select name="items[{{ $index }}][chart_account_id]" required onchange="updateTotals()"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <option value="">Seleccionar...</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $item->chart_account_id == $account->id ? 'selected' : '' }}>{{ $account->code }} - {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                                <select name="items[{{ $index }}][type]" required onchange="updateTotals()"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <option value="debit" {{ $item->type === 'debit' ? 'selected' : '' }}>Debe</option>
                                    <option value="credit" {{ $item->type === 'credit' ? 'selected' : '' }}>Haber</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto *</label>
                                <input type="number" name="items[{{ $index }}][amount]" step="0.01" min="0.01" value="{{ $item->amount }}" required onchange="updateTotals()" oninput="updateTotals()"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                                <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
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
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Debe</p>
                            <p id="total-debit" class="text-xl font-bold text-gray-900 dark:text-white">$0.00</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Haber</p>
                            <p id="total-credit" class="text-xl font-bold text-gray-900 dark:text-white">$0.00</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Diferencia</p>
                        <p id="difference" class="text-lg font-semibold text-gray-900 dark:text-white">$0.00</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('journal-entries.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                    Actualizar Póliza
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = {{ $journalEntry->items->count() }};
const accounts = @json($accounts);

function addItem() {
    const container = document.getElementById('items-container');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border border-gray-300 dark:border-gray-700 rounded-lg p-4';
    itemDiv.innerHTML = `
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable *</label>
                <select name="items[${itemIndex}][chart_account_id]" required onchange="updateTotals()"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Seleccionar...</option>
                    ${accounts.map(acc => `<option value="${acc.id}">${acc.code} - ${acc.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                <select name="items[${itemIndex}][type]" required onchange="updateTotals()"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="debit">Debe</option>
                    <option value="credit">Haber</option>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto *</label>
                <input type="number" name="items[${itemIndex}][amount]" step="0.01" min="0.01" required onchange="updateTotals()" oninput="updateTotals()"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>
            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                <input type="text" name="items[${itemIndex}][description]"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
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
    updateTotals();
}

function removeItem(button) {
    button.closest('div[class*="border"]').remove();
    updateTotals();
}

function updateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;

    document.querySelectorAll('[name*="[amount]"]').forEach(input => {
        const amount = parseFloat(input.value) || 0;
        const typeSelect = input.closest('.grid').querySelector('[name*="[type]"]');
        const type = typeSelect ? typeSelect.value : 'debit';

        if (type === 'debit') {
            totalDebit += amount;
        } else {
            totalCredit += amount;
        }
    });

    document.getElementById('total-debit').textContent = '$' + totalDebit.toFixed(2);
    document.getElementById('total-credit').textContent = '$' + totalCredit.toFixed(2);
    
    const difference = totalDebit - totalCredit;
    const diffElement = document.getElementById('difference');
    diffElement.textContent = '$' + Math.abs(difference).toFixed(2);
    
    if (difference === 0) {
        diffElement.className = 'text-lg font-semibold text-green-600 dark:text-green-400';
    } else {
        diffElement.className = 'text-lg font-semibold text-red-600 dark:text-red-400';
    }
}

// Actualizar totales al cargar la página
updateTotals();
</script>
@endsection



