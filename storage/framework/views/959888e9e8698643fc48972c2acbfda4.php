

<?php $__env->startSection('title', 'Ver Análisis Financiero'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav class="text-sm">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><span class="mx-2">/</span></li>
        <li><a href="<?php echo e(route('financial-analysis.index')); ?>" class="text-gray-500 hover:text-gray-700">Análisis Financiero</a></li>
        <li><span class="mx-2">/</span></li>
        <li><span class="text-gray-700"><?php echo e($financialAnalysis->name); ?></span></li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($financialAnalysis->name); ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Tipo: <span class="font-semibold"><?php echo e(strtoupper($financialAnalysis->analysis_type)); ?></span>
            </p>
        </div>
        <a href="<?php echo e(route('financial-analysis.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
            Volver
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información General -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información del Análisis</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tipo de Análisis</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <?php if($financialAnalysis->analysis_type === 'van'): ?>
                                VAN - Valor Actual Neto
                            <?php elseif($financialAnalysis->analysis_type === 'tir'): ?>
                                TIR - Tasa Interna de Retorno
                            <?php elseif($financialAnalysis->analysis_type === 'break_even'): ?>
                                Punto de Equilibrio
                            <?php elseif($financialAnalysis->analysis_type === 'payback'): ?>
                                Periodo de Recuperación
                            <?php elseif($financialAnalysis->analysis_type === 'profitability_index'): ?>
                                Índice de Rentabilidad
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Creado por</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <?php echo e($financialAnalysis->creator->name ?? 'N/A'); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Creación</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <?php echo e($financialAnalysis->created_at ? $financialAnalysis->created_at->format('d/m/Y H:i') : 'N/A'); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Campos según el tipo de análisis -->
            <?php if(in_array($financialAnalysis->analysis_type, ['van', 'tir', 'payback', 'profitability_index'])): ?>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Datos de Inversión</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Inversión Inicial</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            $<?php echo e(number_format($financialAnalysis->initial_investment, 2)); ?>

                        </p>
                    </div>
                    <?php if($financialAnalysis->discount_rate > 0): ?>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tasa de Descuento</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            <?php echo e(number_format($financialAnalysis->discount_rate, 2)); ?>%
                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($financialAnalysis->cash_flows) && is_array($financialAnalysis->cash_flows)): ?>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Flujos de Efectivo</p>
                    <div class="grid grid-cols-4 gap-2">
                        <?php $__currentLoopData = $financialAnalysis->cash_flows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $flow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded p-2 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Período <?php echo e($index + 1); ?></p>
                            <p class="font-semibold text-gray-900 dark:text-white">$<?php echo e(number_format($flow, 2)); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($financialAnalysis->analysis_type === 'break_even'): ?>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Datos del Punto de Equilibrio</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Costos Fijos</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            $<?php echo e(number_format($financialAnalysis->fixed_costs, 2)); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Costo Variable/Unidad</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            $<?php echo e(number_format($financialAnalysis->variable_cost_per_unit, 2)); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Precio Venta/Unidad</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            $<?php echo e(number_format($financialAnalysis->selling_price_per_unit, 2)); ?>

                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($financialAnalysis->notes): ?>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notas</h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap"><?php echo e($financialAnalysis->notes); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Resultados -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Resultado del Análisis</h2>
                
                <?php if($financialAnalysis->analysis_type === 'van'): ?>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Valor Actual Neto</p>
                        <p class="text-3xl font-bold <?php echo e(($results['npv'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                            $<?php echo e(number_format($results['npv'] ?? 0, 2)); ?>

                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <?php echo e(($results['npv'] ?? 0) >= 0 ? 'Proyecto viable' : 'Proyecto no viable'); ?>

                        </p>
                    </div>
                <?php elseif($financialAnalysis->analysis_type === 'tir'): ?>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Tasa Interna de Retorno</p>
                        <p class="text-3xl font-bold text-blue-600">
                            <?php echo e(number_format($results['irr'] ?? 0, 2)); ?>%
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <?php echo e(($results['irr'] ?? 0) >= ($financialAnalysis->discount_rate ?? 0) ? 'Proyecto viable' : 'Proyecto no viable'); ?>

                        </p>
                    </div>
                <?php elseif($financialAnalysis->analysis_type === 'break_even'): ?>
                    <div class="space-y-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Unidades de Equilibrio</p>
                            <p class="text-2xl font-bold text-blue-600">
                                <?php echo e(number_format($results['break_even']['units'] ?? 0, 2)); ?>

                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Monto de Equilibrio</p>
                            <p class="text-2xl font-bold text-blue-600">
                                $<?php echo e(number_format($results['break_even']['amount'] ?? 0, 2)); ?>

                            </p>
                        </div>
                    </div>
                <?php elseif($financialAnalysis->analysis_type === 'payback'): ?>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Periodo de Recuperación</p>
                        <p class="text-3xl font-bold text-blue-600">
                            <?php echo e(number_format($results['payback_period'] ?? 0, 1)); ?>

                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">períodos</p>
                    </div>
                <?php elseif($financialAnalysis->analysis_type === 'profitability_index'): ?>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Índice de Rentabilidad</p>
                        <p class="text-3xl font-bold <?php echo e(($results['profitability_index'] ?? 0) >= 1 ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e(number_format($results['profitability_index'] ?? 0, 2)); ?>

                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <?php echo e(($results['profitability_index'] ?? 0) >= 1 ? 'Proyecto viable' : 'Proyecto no viable'); ?>

                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Margen de Contribución (solo para break even) -->
            <?php if($financialAnalysis->analysis_type === 'break_even'): ?>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Margen de Contribución</h3>
                <?php
                    $contributionMargin = $financialAnalysis->selling_price_per_unit - $financialAnalysis->variable_cost_per_unit;
                    $contributionMarginPercent = $financialAnalysis->selling_price_per_unit > 0 
                        ? ($contributionMargin / $financialAnalysis->selling_price_per_unit) * 100 
                        : 0;
                ?>
                <p class="text-2xl font-bold text-green-600">
                    $<?php echo e(number_format($contributionMargin, 2)); ?>

                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    <?php echo e(number_format($contributionMarginPercent, 2)); ?>% del precio de venta
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/financial-analysis/show.blade.php ENDPATH**/ ?>