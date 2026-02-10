<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValentineController;

// Ruta principal - Página de San Valentín
Route::get('/', [ValentineController::class, 'index'])->name('valentine.index');
Route::post('/respuesta', [ValentineController::class, 'answer'])->name('valentine.answer');

// Endpoint de ping para keep-alive (sin middleware para que sea rápido)
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'message' => 'Server is alive'
    ]);
})->name('ping');

// Rutas públicas (sin tenant)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rutas con autenticación y tenant
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de empresas (solo super admin)
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('tenants', TenantController::class);
    });
    
    // Catálogo de Cuentas Contables
    Route::resource('chart-accounts', ChartAccountController::class);
    Route::post('/chart-accounts/{chartAccount}/restore', [ChartAccountController::class, 'restore'])
        ->name('chart-accounts.restore');
    
    // Pólizas Contables
    Route::resource('journal-entries', JournalEntryController::class);
    Route::post('/journal-entries/{journalEntry}/post', [JournalEntryController::class, 'post'])
        ->name('journal-entries.post');
    
    // Libro Mayor
    Route::get('/general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
    
    // Productos
    Route::resource('products', ProductController::class);
    
    // Categorías de Productos
    Route::resource('product-categories', ProductCategoryController::class);
    
    // Periodos Financieros
    Route::resource('financial-periods', FinancialPeriodController::class);
    
    // Inventario
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{product}/kardex', [InventoryController::class, 'kardex'])
        ->name('inventory.kardex');
    
    // Presupuestos
    Route::resource('budgets', BudgetController::class);
    Route::post('/budgets/{budget}/approve', [BudgetController::class, 'approve'])
        ->name('budgets.approve');
    
    // Periodos Financieros
    Route::resource('financial-periods', FinancialPeriodController::class);
    Route::post('/financial-periods/{financialPeriod}/close', [FinancialPeriodController::class, 'close'])
        ->name('financial-periods.close');
    
    // Estados Financieros
    Route::prefix('financial-statements')->name('financial-statements.')->group(function () {
        Route::get('/balance-sheet', [FinancialStatementController::class, 'balanceSheet'])
            ->name('balance-sheet');
        Route::get('/income-statement', [FinancialStatementController::class, 'incomeStatement'])
            ->name('income-statement');
        Route::get('/cash-flow', [FinancialStatementController::class, 'cashFlowStatement'])
            ->name('cash-flow');
    });
    
    // Análisis Financiero (VAN, TIR, Punto de Equilibrio)
    Route::resource('financial-analysis', FinancialAnalysisController::class);
});
