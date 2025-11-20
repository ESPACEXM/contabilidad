

<?php $__env->startSection('title', 'Kardex de ' . $product->name); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="<?php echo e(route('products.index')); ?>" class="text-gray-500 hover:text-gray-700">Productos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="<?php echo e(route('products.show', $product)); ?>" class="text-gray-500 hover:text-gray-700"><?php echo e($product->name); ?></a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700">Kardex</span></li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kardex de <?php echo e($product->name); ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                SKU: <?php echo e($product->sku ?? 'N/A'); ?> | Método: <?php echo e(strtoupper($product->valuation_method)); ?>

            </p>
        </div>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('products.show', $product)); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver al Producto
            </a>
            <a href="<?php echo e(route('inventory.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Nuevo Movimiento
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Referencia</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Entrada</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Salida</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo Unit.</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Saldo</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo Promedio</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $kardex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($entry['movement']->movement_date->format('d/m/Y')); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <span class="px-2 py-1 rounded text-xs
                                <?php echo e(in_array($entry['movement']->type, ['entry', 'purchase', 'return']) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                                <?php echo e(ucfirst($entry['movement']->type)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($entry['movement']->reference ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            <?php if(in_array($entry['movement']->type, ['entry', 'purchase', 'return'])): ?>
                                <?php echo e(number_format($entry['movement']->quantity, 2)); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            <?php if(!in_array($entry['movement']->type, ['entry', 'purchase', 'return'])): ?>
                                <?php echo e(number_format($entry['movement']->quantity, 2)); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            $<?php echo e(number_format($entry['movement']->unit_cost, 2)); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">
                            <?php echo e(number_format($entry['running_balance'], 2)); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            $<?php echo e(number_format($entry['running_cost'], 2)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay movimientos registrados para este producto.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($kardex->count() > 0): ?>
                <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold">
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-right">Saldo Final:</td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                            <?php echo e(number_format($kardex->last()['running_balance'] ?? 0, 2)); ?>

                        </td>
                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                            $<?php echo e(number_format($kardex->last()['running_cost'] ?? 0, 2)); ?>

                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/inventory/kardex.blade.php ENDPATH**/ ?>