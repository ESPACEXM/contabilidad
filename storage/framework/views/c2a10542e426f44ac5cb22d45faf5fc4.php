<?php $__env->startSection('title', 'Crear Presupuesto'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="<?php echo e(route('budgets.index')); ?>" class="text-gray-500 hover:text-gray-700">Presupuestos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Crear</span></li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Presupuesto</h1>
        <a href="<?php echo e(route('budgets.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
            Volver
        </a>
    </div>

    <?php if($periods->isEmpty()): ?>
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-yellow-800 dark:text-yellow-300 font-semibold">No hay periodos financieros disponibles</p>
        </div>
        <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-2">
            Debes crear un periodo financiero antes de crear un presupuesto.
            <a href="<?php echo e(route('financial-periods.create')); ?>" class="underline ml-1">Crear periodo financiero</a>
        </p>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('budgets.store')); ?>" method="POST" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6" id="budgetForm">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="financial_period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Periodo Financiero *
                </label>
                <select name="financial_period_id" id="financial_period_id" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    <?php echo e($periods->isEmpty() ? 'disabled' : ''); ?>>
                    <option value="">Seleccione un período</option>
                    <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($period->id); ?>" <?php echo e(old('financial_period_id') == $period->id ? 'selected' : ''); ?>>
                            <?php echo e($period->name); ?> (<?php echo e($period->start_date->format('d/m/Y')); ?> - <?php echo e($period->end_date->format('d/m/Y')); ?>)
                            <?php if($period->is_closed): ?>
                                - Cerrado
                            <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['financial_period_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="budget_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipo de Presupuesto *
                </label>
                <select name="budget_type" id="budget_type" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="operational">Operacional</option>
                    <option value="capital">Capital</option>
                    <option value="departmental">Departamental</option>
                </select>
                <?php $__errorArgs = ['budget_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre del Presupuesto *
                </label>
                <input type="text" name="name" id="name" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?php echo e(old('name')); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Departamento
                </label>
                <input type="text" name="department" id="department"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?php echo e(old('department')); ?>">
            </div>

            <div>
                <label for="cost_center" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Centro de Costo
                </label>
                <input type="text" name="cost_center" id="cost_center"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?php echo e(old('cost_center')); ?>">
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Notas
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('notes')); ?></textarea>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Items del Presupuesto</h3>
                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                    + Agregar Item
                </button>
            </div>
            <div id="items-container" class="space-y-4">
                <div class="item-row border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuenta Contable</label>
                            <select name="items[0][chart_account_id]" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Seleccione...</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($account->id); ?>"><?php echo e($account->code); ?> - <?php echo e($account->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción *</label>
                            <input type="text" name="items[0][description]" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Presupuestado *</label>
                            <input type="number" name="items[0][budgeted_amount]" step="0.01" min="0.01" required onchange="updateTotal()" oninput="updateTotal()"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mes</label>
                            <select name="items[0][month]"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Todos</option>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-end">
                        <button type="button" onclick="removeItem(this)" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-1 px-3 rounded-lg">
                            × Eliminar
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Presupuestado</p>
                <p id="total-amount" class="text-2xl font-bold text-gray-900 dark:text-white">$0.00</p>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t dark:border-gray-700">
            <a href="<?php echo e(route('budgets.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Crear Presupuesto
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
const accounts = <?php echo json_encode($accounts, 15, 512) ?>;
const months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const template = container.querySelector('.item-row').cloneNode(true);
    
    // Actualizar índices
    const inputs = template.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/\[(\d+)\]/, `[${itemIndex}]`);
        }
    });
    
    // Limpiar valores
    inputs.forEach(input => {
        if (input.type !== 'hidden') {
            input.value = '';
        }
    });
    
    // Re-attach event listeners
    const amountInput = template.querySelector('[name*="[budgeted_amount]"]');
    if (amountInput) {
        amountInput.addEventListener('change', updateTotal);
        amountInput.addEventListener('input', updateTotal);
    }
    
    container.appendChild(template);
    itemIndex++;
    updateTotal();
});

function removeItem(button) {
    const itemRow = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        itemRow.remove();
        updateTotal();
    } else {
        alert('Debes tener al menos un item en el presupuesto.');
    }
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('[name*="[budgeted_amount]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
}

// Validar formulario antes de enviar
document.getElementById('budgetForm').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.item-row');
    if (items.length === 0) {
        e.preventDefault();
        alert('Debes agregar al menos un item al presupuesto.');
        return false;
    }
    
    let hasValidItem = false;
    items.forEach(item => {
        const description = item.querySelector('[name*="[description]"]').value.trim();
        const amount = parseFloat(item.querySelector('[name*="[budgeted_amount]"]').value);
        if (description && amount > 0) {
            hasValidItem = true;
        }
    });
    
    if (!hasValidItem) {
        e.preventDefault();
        alert('Debes completar al menos un item con descripción y monto válido.');
        return false;
    }
});

updateTotal();
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/budgets/create.blade.php ENDPATH**/ ?>