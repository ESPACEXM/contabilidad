

<?php $__env->startSection('title', 'Balance General'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Balance General</h1>
        <form method="GET" class="flex gap-2">
            <input type="date" name="date" value="<?php echo e($endDate); ?>" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Consultar</button>
        </form>
    </div>

    <!-- Gráficas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Gráfica de Activos -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Distribución de Activos</h3>
            <canvas id="assetsChart" height="300"></canvas>
        </div>
        
        <!-- Gráfica de Pasivos y Capital -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Distribución de Pasivos y Capital</h3>
            <canvas id="liabilitiesChart" height="300"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Al <?php echo e(\Carbon\Carbon::parse($endDate)->format('d/m/Y')); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">ACTIVO</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $balanceSheet['assets']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(abs($account['balance']) > 0.01): ?>
                        <div class="flex justify-between text-gray-900 dark:text-white">
                            <span><?php echo e($account['code']); ?> - <?php echo e($account['name']); ?></span>
                            <span>$<?php echo e(number_format(abs($account['balance']), 2)); ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-t pt-2 font-bold flex justify-between text-gray-900 dark:text-white">
                        <span>Total Activo</span>
                        <span>$<?php echo e(number_format($balanceSheet['total_assets'], 2)); ?></span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">PASIVO Y CAPITAL</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $balanceSheet['liabilities']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(abs($account['balance']) > 0.01): ?>
                        <div class="flex justify-between text-gray-900 dark:text-white">
                            <span><?php echo e($account['code']); ?> - <?php echo e($account['name']); ?></span>
                            <span>$<?php echo e(number_format(abs($account['balance']), 2)); ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $balanceSheet['equity']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(abs($account['balance']) > 0.01): ?>
                        <div class="flex justify-between text-gray-900 dark:text-white">
                            <span><?php echo e($account['code']); ?> - <?php echo e($account['name']); ?></span>
                            <span>$<?php echo e(number_format(abs($account['balance']), 2)); ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-t pt-2 font-bold flex justify-between text-gray-900 dark:text-white">
                        <span>Total Pasivo + Capital</span>
                        <span>$<?php echo e(number_format($balanceSheet['total_liabilities'] + $balanceSheet['total_equity'], 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Datos para gráficas
    const assetsData = {
        labels: [
            <?php $__currentLoopData = $balanceSheet['assets']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                '<?php echo e($account['code']); ?> - <?php echo e($account['name']); ?>',
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ],
        values: [
            <?php $__currentLoopData = $balanceSheet['assets']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                <?php echo e(abs($account['balance'])); ?>,
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ]
    };

    const liabilitiesData = {
        labels: [
            <?php $__currentLoopData = $balanceSheet['liabilities']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                '<?php echo e($account['code']); ?> - <?php echo e($account['name']); ?>',
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $balanceSheet['equity']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                '<?php echo e($account['code']); ?> - <?php echo e($account['name']); ?>',
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ],
        values: [
            <?php $__currentLoopData = $balanceSheet['liabilities']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                <?php echo e(abs($account['balance'])); ?>,
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $balanceSheet['equity']['accounts'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(abs($account['balance']) > 0.01): ?>
                <?php echo e(abs($account['balance'])); ?>,
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ]
    };

    // Configuración de colores
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#f3f4f6' : '#1f2937';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    // Gráfica de Activos
    const assetsCtx = document.getElementById('assetsChart');
    if (assetsCtx && assetsData.values.length > 0) {
        new Chart(assetsCtx, {
            type: 'doughnut',
            data: {
                labels: assetsData.labels,
                datasets: [{
                    data: assetsData.values,
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#06b6d4', '#ec4899', '#84cc16', '#f97316', '#6366f1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return label + ': $' + value.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Pasivos y Capital
    const liabilitiesCtx = document.getElementById('liabilitiesChart');
    if (liabilitiesCtx && liabilitiesData.values.length > 0) {
        new Chart(liabilitiesCtx, {
            type: 'doughnut',
            data: {
                labels: liabilitiesData.labels,
                datasets: [{
                    data: liabilitiesData.values,
                    backgroundColor: [
                        '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6',
                        '#06b6d4', '#ec4899', '#84cc16', '#f97316', '#6366f1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return label + ': $' + value.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto Contabilidad\resources\views/financial-statements/balance-sheet.blade.php ENDPATH**/ ?>