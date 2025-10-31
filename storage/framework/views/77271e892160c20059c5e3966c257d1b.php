

<?php $__env->startSection('title', 'Detalle de Cuenta Contable'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="<?php echo e(route('chart-accounts.index')); ?>" class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 md:ml-2">Catálogo de Cuentas</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2"><?php echo e($chartAccount->code); ?></span>
            </div>
        </li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($chartAccount->code); ?> - <?php echo e($chartAccount->name); ?></h1>
                <?php if($chartAccount->description): ?>
                    <p class="mt-2 text-gray-600 dark:text-gray-400"><?php echo e($chartAccount->description); ?></p>
                <?php endif; ?>
            </div>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('chart-accounts.edit', $chartAccount)); ?>" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                    Editar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Código</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($chartAccount->code); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nombre</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($chartAccount->name); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo</label>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                    <?php if($chartAccount->type == 'activo'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                    <?php elseif($chartAccount->type == 'pasivo'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    <?php elseif($chartAccount->type == 'capital'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                    <?php elseif($chartAccount->type == 'ingreso'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    <?php else: ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    <?php endif; ?>">
                    <?php echo e(ucfirst($chartAccount->type)); ?>

                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Naturaleza</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(ucfirst($chartAccount->nature)); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nivel</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(ucfirst(str_replace('_', ' ', $chartAccount->level))); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Estado</label>
                <?php if($chartAccount->is_active): ?>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Activa
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        Inactiva
                    </span>
                <?php endif; ?>
            </div>

            <?php if($chartAccount->parent): ?>
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cuenta Padre</label>
                <a href="<?php echo e(route('chart-accounts.show', $chartAccount->parent)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                    <?php echo e($chartAccount->parent->code); ?> - <?php echo e($chartAccount->parent->name); ?>

                </a>
            </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Saldo Inicial</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">$<?php echo e(number_format($chartAccount->initial_balance, 2)); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Permite Movimientos</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($chartAccount->allows_movements ? 'Sí' : 'No'); ?></p>
            </div>
        </div>
    </div>

    <?php if($chartAccount->children->count() > 0): ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sub-cuentas (<?php echo e($chartAccount->children->count()); ?>)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $chartAccount->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($child->code); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white"><?php echo e($child->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="<?php echo e(route('chart-accounts.show', $child)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/chart-accounts/show.blade.php ENDPATH**/ ?>