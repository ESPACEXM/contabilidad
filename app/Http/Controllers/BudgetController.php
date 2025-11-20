<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\FinancialPeriod;
use App\Models\ChartAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::forTenant(Auth::user()->tenant_id)
            ->with(['financialPeriod', 'creator']);

        if ($request->filled('period_id')) {
            $query->where('financial_period_id', $request->period_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $budgets = $query->orderBy('created_at', 'desc')->paginate(15);
        $periods = FinancialPeriod::forTenant(Auth::user()->tenant_id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('budgets.index', compact('budgets', 'periods'));
    }

    public function create()
    {
        // Si no hay periodos abiertos, mostrar todos los periodos
        $periods = FinancialPeriod::forTenant(Auth::user()->tenant_id)
            ->open()
            ->orderBy('start_date', 'desc')
            ->get();

        // Si no hay periodos abiertos, obtener todos los periodos
        if ($periods->isEmpty()) {
            $periods = FinancialPeriod::forTenant(Auth::user()->tenant_id)
                ->orderBy('start_date', 'desc')
                ->get();
        }

        $accounts = ChartAccount::forTenant(Auth::user()->tenant_id)
            ->where('allows_movements', true)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('budgets.create', compact('periods', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'financial_period_id' => 'required|exists:financial_periods,id',
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:255',
            'budget_type' => 'required|in:operational,capital,departmental',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.chart_account_id' => 'nullable|exists:chart_accounts,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.budgeted_amount' => 'required|numeric|min:0.01',
            'items.*.month' => 'nullable|integer|between:1,12',
            'items.*.notes' => 'nullable|string',
        ]);

        $totalAmount = collect($validated['items'])->sum('budgeted_amount');

        $budget = Budget::create([
            'tenant_id' => Auth::user()->tenant_id,
            'financial_period_id' => $validated['financial_period_id'],
            'name' => $validated['name'],
            'department' => $validated['department'] ?? null,
            'cost_center' => $validated['cost_center'] ?? null,
            'budget_type' => $validated['budget_type'],
            'total_amount' => $totalAmount,
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            BudgetItem::create([
                'budget_id' => $budget->id,
                'chart_account_id' => $item['chart_account_id'] ?? null,
                'description' => $item['description'],
                'budgeted_amount' => $item['budgeted_amount'],
                'month' => $item['month'] ?? null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('budgets.index')
            ->with('success', 'Presupuesto creado exitosamente.');
    }

    public function show(Budget $budget)
    {
        $budget->load(['items.chartAccount', 'financialPeriod', 'creator', 'approver']);
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        if ($budget->status === 'approved' || $budget->status === 'closed') {
            return redirect()->route('budgets.index')
                ->with('error', 'No se puede editar un presupuesto aprobado o cerrado.');
        }

        $periods = FinancialPeriod::forTenant(Auth::user()->tenant_id)
            ->open()
            ->orderBy('start_date', 'desc')
            ->get();

        $accounts = ChartAccount::forTenant(Auth::user()->tenant_id)
            ->where('allows_movements', true)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $budget->load('items');

        return view('budgets.edit', compact('budget', 'periods', 'accounts'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->status === 'approved' || $budget->status === 'closed') {
            return redirect()->route('budgets.index')
                ->with('error', 'No se puede editar un presupuesto aprobado o cerrado.');
        }

        $validated = $request->validate([
            'financial_period_id' => 'required|exists:financial_periods,id',
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:255',
            'budget_type' => 'required|in:operational,capital,departmental',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.chart_account_id' => 'nullable|exists:chart_accounts,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.budgeted_amount' => 'required|numeric|min:0.01',
            'items.*.month' => 'nullable|integer|between:1,12',
            'items.*.notes' => 'nullable|string',
        ]);

        $totalAmount = collect($validated['items'])->sum('budgeted_amount');

        $budget->update([
            'financial_period_id' => $validated['financial_period_id'],
            'name' => $validated['name'],
            'department' => $validated['department'] ?? null,
            'cost_center' => $validated['cost_center'] ?? null,
            'budget_type' => $validated['budget_type'],
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
        ]);

        $budget->items()->delete();

        foreach ($validated['items'] as $item) {
            BudgetItem::create([
                'budget_id' => $budget->id,
                'chart_account_id' => $item['chart_account_id'] ?? null,
                'description' => $item['description'],
                'budgeted_amount' => $item['budgeted_amount'],
                'month' => $item['month'] ?? null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('budgets.index')
            ->with('success', 'Presupuesto actualizado exitosamente.');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->status === 'approved' || $budget->status === 'closed') {
            return redirect()->route('budgets.index')
                ->with('error', 'No se puede eliminar un presupuesto aprobado o cerrado.');
        }

        $budget->delete();
        return redirect()->route('budgets.index')
            ->with('success', 'Presupuesto eliminado exitosamente.');
    }

    public function approve(Budget $budget)
    {
        $budget->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('budgets.index')
            ->with('success', 'Presupuesto aprobado exitosamente.');
    }
}
