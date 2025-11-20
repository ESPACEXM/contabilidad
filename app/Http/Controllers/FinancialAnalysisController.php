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
        $rules = [
            'name' => 'required|string|max:255',
            'analysis_type' => 'required|in:van,tir,break_even',
            'notes' => 'nullable|string',
        ];

        // Validaciones condicionales según el tipo de análisis
        if (in_array($request->analysis_type, ['van', 'tir'])) {
            $rules['initial_investment'] = 'required|numeric|min:0';
            $rules['cash_flows_input'] = 'required|string';
            if ($request->analysis_type === 'van') {
                $rules['discount_rate'] = 'required|numeric|min:0|max:100';
            }
        } else if ($request->analysis_type === 'break_even') {
            $rules['fixed_costs'] = 'required|numeric|min:0';
            $rules['variable_cost_per_unit'] = 'required|numeric|min:0';
            $rules['selling_price_per_unit'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Procesar flujos de efectivo
        $cashFlows = [];
        if ($request->has('cash_flows_input') && !empty($request->cash_flows_input)) {
            // Si viene como texto separado por comas
            $cashFlows = array_map(function($value) {
                return floatval(trim($value));
            }, explode(',', $request->cash_flows_input));
            $cashFlows = array_filter($cashFlows, function($value) {
                return $value !== 0 && !is_nan($value);
            });
            $cashFlows = array_values($cashFlows); // Reindexar
        } else if ($request->has('cash_flows')) {
            if (is_array($request->cash_flows)) {
                $cashFlows = $request->cash_flows;
            } else if (is_string($request->cash_flows)) {
                $decoded = json_decode($request->cash_flows, true);
                $cashFlows = is_array($decoded) ? $decoded : [];
            }
        }

        $analysis = FinancialAnalysis::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'analysis_type' => $validated['analysis_type'],
            'initial_investment' => $validated['initial_investment'] ?? 0,
            'discount_rate' => $validated['discount_rate'] ?? 0,
            'cash_flows' => $cashFlows,
            'fixed_costs' => $validated['fixed_costs'] ?? 0,
            'variable_cost_per_unit' => $validated['variable_cost_per_unit'] ?? 0,
            'selling_price_per_unit' => $validated['selling_price_per_unit'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Calcular según el tipo de análisis
        $this->calculateAnalysis($analysis);
        $analysis->refresh();

        return redirect()->route('financial-analysis.show', $analysis)
            ->with('success', 'Análisis financiero creado exitosamente.');
    }

    public function show(FinancialAnalysis $financialAnalysis)
    {
        // El método resolveRouteBinding ya filtra por tenant, pero verificamos por seguridad
        if ($financialAnalysis->tenant_id !== Auth::user()->tenant_id) {
            abort(404);
        }

        // Cargar relaciones
        $financialAnalysis->load(['creator', 'tenant']);

        // Recalcular si no hay resultado o si los datos han cambiado
        if (!$financialAnalysis->result_value || $financialAnalysis->result_value == 0) {
            $result = $this->calculateAnalysis($financialAnalysis);
            $financialAnalysis->refresh();
        }

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
        }

        return view('financial-analysis.show', compact('financialAnalysis', 'results'));
    }

    protected function calculateAnalysis(FinancialAnalysis $analysis): void
    {
        switch($analysis->analysis_type) {
            case 'van':
                $analysis->calculateNPV();
                break;
            case 'tir':
                $analysis->calculateIRR();
                break;
            case 'break_even':
                $breakEven = $analysis->calculateBreakEven();
                // Guardar el monto del punto de equilibrio como result_value
                $analysis->result_value = $breakEven['amount'] ?? 0;
                $analysis->save();
                break;
        }
    }
}
