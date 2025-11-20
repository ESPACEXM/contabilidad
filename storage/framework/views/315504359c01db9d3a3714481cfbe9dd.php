

<?php $__env->startSection('title', 'Análisis Financiero'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Análisis Financiero</h1>
        <a href="<?php echo e(route('financial-analysis.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Nuevo Análisis
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <a href="<?php echo e(route('financial-analysis.create')); ?>?type=van" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">VAN</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Valor Actual Neto</p>
        </a>
        <a href="<?php echo e(route('financial-analysis.create')); ?>?type=tir" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">TIR</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Tasa Interna de Retorno</p>
        </a>
        <a href="<?php echo e(route('financial-analysis.create')); ?>?type=break_even" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">Punto de Equilibrio</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Análisis de punto de equilibrio</p>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Resultado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $analyses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analysis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        <?php echo e($analysis->name); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <?php echo e(strtoupper($analysis->analysis_type)); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <?php if($analysis->analysis_type === 'break_even'): ?>
                            <?php
                                $breakEven = $analysis->calculateBreakEven();
                            ?>
                            <?php echo e(number_format($breakEven['units'] ?? 0, 2)); ?> unidades / $<?php echo e(number_format($breakEven['amount'] ?? 0, 2)); ?>

                        <?php elseif($analysis->analysis_type === 'tir'): ?>
                            <?php echo e(number_format($analysis->result_value ?? 0, 2)); ?>%
                        <?php else: ?>
                            $<?php echo e(number_format($analysis->result_value ?? 0, 2)); ?>

                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <?php echo e($analysis->created_at->format('d/m/Y')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="<?php echo e(route('financial-analysis.show', $analysis)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Ver</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay análisis registrados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-6 py-4 border-t"><?php echo e($analyses->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/financial-analysis/index.blade.php ENDPATH**/ ?>