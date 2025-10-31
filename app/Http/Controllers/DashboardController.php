<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JournalEntry;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\Budget;
use App\Models\ChartAccount;
use App\Services\FinancialStatementService;
use App\Services\FinancialRatioService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        $tenantId = $user->tenant_id;

        // KPIs principales
        $kpis = [
            'journal_entries' => JournalEntry::forTenant($tenantId)->count(),
            'posted_entries' => JournalEntry::forTenant($tenantId)->posted()->count(),
            'total_products' => Product::forTenant($tenantId)->count(),
            'low_stock_products' => Product::forTenant($tenantId)->lowStock()->count(),
            'active_budgets' => Budget::forTenant($tenantId)->active()->count(),
            'total_accounts' => ChartAccount::forTenant($tenantId)->count(),
        ];

        // Movimientos recientes de inventario
        $recentMovements = InventoryMovement::forTenant($tenantId)
            ->with(['product', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pólizas recientes
        $recentEntries = JournalEntry::forTenant($tenantId)
            ->with(['creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estadísticas por tipo de cuenta
        $accountsByType = ChartAccount::forTenant($tenantId)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Estadísticas de productos por categoría
        $productsByCategory = Product::forTenant($tenantId)
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->selectRaw('product_categories.name, COUNT(*) as count')
            ->groupBy('product_categories.name')
            ->pluck('count', 'product_categories.name')
            ->toArray();

        // Balance general (simplificado)
        $statementService = new FinancialStatementService($tenant);
        $statementService->setPeriod(
            Carbon::now()->startOfYear(),
            Carbon::now()
        );

        $balanceSheet = $statementService->getBalanceSheet();
        $incomeStatement = $statementService->getIncomeStatement();

        // Ratios financieros
        $ratioService = new FinancialRatioService($statementService);
        $ratios = $ratioService->getAllRatios();

        // Presupuestos vs Realizado
        $budgets = Budget::forTenant($tenantId)
            ->approved()
            ->orWhere('status', 'active')
            ->with(['financialPeriod'])
            ->limit(5)
            ->get()
            ->map(function($budget) {
                return [
                    'name' => $budget->name,
                    'budgeted' => $budget->total_amount,
                    'executed' => $budget->executed_amount,
                    'variance' => $budget->variance,
                    'variance_percentage' => $budget->variance_percentage,
                ];
            });

        return view('dashboard', compact(
            'user',
            'tenant',
            'kpis',
            'recentMovements',
            'recentEntries',
            'accountsByType',
            'productsByCategory',
            'balanceSheet',
            'incomeStatement',
            'ratios',
            'budgets'
        ));
    }
}
