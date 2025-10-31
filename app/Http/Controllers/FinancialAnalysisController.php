<?php

namespace App\Http\Controllers;

use App\Models\FinancialAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialAnalysisController extends Controller
{
    public function index()
    {
        $analyses = FinancialAnalysis::forTenant(Auth::user()->tenant_id)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('financial-analysis.index', compact('analyses'));
    }

    public function create()
    {
        return view('financial-analysis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'analysis_type' => 'required|in:van,tir,break_even,payback,profitability_index',
            'initial_investment' => 'required|numeric|min:0',
            'discount_rate' => 'nullable|numeric|min:0|max:100',
            'cash_flows' => 'nullable|array',
            'cash_flows.*' => 'nullable|numeric',
            'fixed_costs' => 'nullable|numeric|min:0',
            'variable_cost_per_unit' => 'nullable|numeric|min:0',
            'selling_price_per_unit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $analysis = FinancialAnalysis::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'analysis_type' => $validated['analysis_type'],
            'initial_investment' => $validated['initial_investment'],
            'discount_rate' => $validated['discount_rate'] ?? 0,
            'cash_flows' => $validated['cash_flows'] ?? [],
            'fixed_costs' => $validated['fixed_costs'] ?? 0,
            'variable_cost_per_unit' => $validated['variable_cost_per_unit'] ?? 0,
            'selling_price_per_unit' => $validated['selling_price_per_unit'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Calcular según el tipo de análisis
        $result = $this->calculateAnalysis($analysis);
        $analysis->update(['result_value' => $result]);

        return redirect()->route('financial-analysis.show', $analysis)
            ->with('success', 'Análisis financiero creado exitosamente.');
    }

    public function show(FinancialAnalysis $financialAnalysis)
    {
        $financialAnalysis->load('creator');

        $results = [];
        switch ($financialAnalysis->analysis_type) {
            case 'van':
                $results['npv'] = $financialAnalysis->calculateNPV();
                break;
            case 'tir':
                $results['irr'] = $financialAnalysis->calculateIRR();
                break;
            case 'break_even':
                $results['break_even'] = $financialAnalysis->calculateBreakEven();
                break;
            case 'payback':
                $results['payback_period'] = $financialAnalysis->calculatePaybackPeriod();
                break;
            case 'profitability_index':
                $results['profitability_index'] = $financialAnalysis->calculateProfitabilityIndex();
                break;
        }

        return view('financial-analysis.show', compact('financialAnalysis', 'results'));
    }

    protected function calculateAnalysis(FinancialAnalysis $analysis): float
    {
        return match($analysis->analysis_type) {
            'van' => $analysis->calculateNPV(),
            'tir' => $analysis->calculateIRR(),
            'payback' => $analysis->calculatePaybackPeriod(),
            'profitability_index' => $analysis->calculateProfitabilityIndex(),
            default => 0,
        };
    }
}
