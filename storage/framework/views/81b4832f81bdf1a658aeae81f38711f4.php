

<?php $__env->startSection('title', 'Ver Producto'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="<?php echo e(route('products.index')); ?>" class="text-gray-500 hover:text-gray-700">Productos</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700"><?php echo e($product->name); ?></span></li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($product->name); ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                <?php if($product->sku): ?> SKU: <?php echo e($product->sku); ?> | <?php endif; ?>
                <?php echo e(ucfirst($product->type)); ?>

            </p>
        </div>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('products.edit', $product)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Editar
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información General</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Categoría</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($product->category->name ?? 'Sin categoría'); ?></p>
                </div>
                <?php if($product->sku): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">SKU</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($product->sku); ?></p>
                </div>
                <?php endif; ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tipo</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e(ucfirst($product->type)); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unidad de Medida</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($product->unit_of_measure); ?></p>
                </div>
                <?php if($product->description): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Descripción</p>
                    <p class="text-gray-900 dark:text-white"><?php echo e($product->description); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información Financiera</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Precio Unitario</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">$<?php echo e(number_format($product->unit_price, 2)); ?></p>
                </div>
                <?php if($product->cost_price): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Costo</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">$<?php echo e(number_format($product->cost_price, 2)); ?></p>
                </div>
                <?php endif; ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Método de Valuación</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e(strtoupper($product->valuation_method)); ?></p>
                </div>
            </div>
        </div>

        <?php if($product->track_inventory): ?>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Inventario</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Actual</p>
                    <p class="text-2xl font-bold <?php echo e($product->isLowStock() ? 'text-red-600' : 'text-gray-900 dark:text-white'); ?>">
                        <?php echo e(number_format($product->stock_quantity, 2)); ?> <?php echo e($product->unit_of_measure); ?>

                    </p>
                    <?php if($product->isLowStock()): ?>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">⚠ Stock bajo</p>
                    <?php endif; ?>
                </div>
                <?php if($product->min_stock): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Mínimo</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($product->min_stock, 2)); ?> <?php echo e($product->unit_of_measure); ?></p>
                </div>
                <?php endif; ?>
                <?php if($product->max_stock): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Máximo</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($product->max_stock, 2)); ?> <?php echo e($product->unit_of_measure); ?></p>
                </div>
                <?php endif; ?>
                <div class="mt-4">
                    <a href="<?php echo e(route('inventory.kardex', $product)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                        Ver Kardex
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if($product->movements->count() > 0): ?>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Últimos Movimientos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo Unitario</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $product->movements->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($movement->movement_date->format('d/m/Y')); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e(ucfirst($movement->type)); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            <?php echo e(number_format($movement->quantity, 2)); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            $<?php echo e(number_format($movement->unit_cost, 2)); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                            $<?php echo e(number_format($movement->total_cost, 2)); ?>

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




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/products/show.blade.php ENDPATH**/ ?>