

<?php $__env->startSection('title', 'Catálogo de Cuentas Contables'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 md:ml-2">Catálogo de Cuentas</span>
            </div>
        </li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Catálogo de Cuentas Contables</h1>
        <a href="<?php echo e(route('chart-accounts.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Cuenta
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form method="GET" action="<?php echo e(route('chart-accounts.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Código o nombre..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Todos</option>
                    <option value="activo" <?php echo e(request('type') == 'activo' ? 'selected' : ''); ?>>Activo</option>
                    <option value="pasivo" <?php echo e(request('type') == 'pasivo' ? 'selected' : ''); ?>>Pasivo</option>
                    <option value="capital" <?php echo e(request('type') == 'capital' ? 'selected' : ''); ?>>Capital</option>
                    <option value="ingreso" <?php echo e(request('type') == 'ingreso' ? 'selected' : ''); ?>>Ingreso</option>
                    <option value="egreso" <?php echo e(request('type') == 'egreso' ? 'selected' : ''); ?>>Egreso</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nivel</label>
                <select name="level" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Todos</option>
                    <option value="cuenta_mayor" <?php echo e(request('level') == 'cuenta_mayor' ? 'selected' : ''); ?>>Cuenta Mayor</option>
                    <option value="subcuenta" <?php echo e(request('level') == 'subcuenta' ? 'selected' : ''); ?>>Subcuenta</option>
                    <option value="auxiliar" <?php echo e(request('level') == 'auxiliar' ? 'selected' : ''); ?>>Auxiliar</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Tabla de Cuentas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nivel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Naturaleza</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($account->code); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo e($account->name); ?></div>
                                <?php if($account->parent): ?>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Padre: <?php echo e($account->parent->code); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php if($account->type == 'activo'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    <?php elseif($account->type == 'pasivo'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    <?php elseif($account->type == 'capital'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    <?php elseif($account->type == 'ingreso'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    <?php else: ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($account->type)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e(ucfirst(str_replace('_', ' ', $account->level))); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e(ucfirst($account->nature)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($account->is_active): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Activa
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Inactiva
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?php echo e(route('chart-accounts.show', $account)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Ver</a>
                                    <a href="<?php echo e(route('chart-accounts.edit', $account)); ?>" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Editar</a>
                                    <form action="<?php echo e(route('chart-accounts.destroy', $account)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cuenta?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron cuentas contables. <a href="<?php echo e(route('chart-accounts.create')); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Crear una cuenta</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <?php echo e($accounts->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/chart-accounts/index.blade.php ENDPATH**/ ?>