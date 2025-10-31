<?php

namespace App\Http\Controllers;

use App\Models\FinancialProjection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialProjectionController extends Controller
{
    public function index()
    {
        $projections = FinancialProjection::forTenant(Auth::user()->tenant_id)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('financial-projections.index', compact('projections'));
    }

    public function create()
    {
        return view('financial-projections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scenario' => 'required|in:optimistic,expected,pessimistic',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'projection_periods' => 'required|integer|min:1|max:60',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'growth_rate' => 'required|numeric|min:-100|max:100',
            'assumptions' => 'nullable|string',
        ]);

        $projection = FinancialProjection::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'scenario' => $validated['scenario'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'projection_periods' => $validated['projection_periods'],
            'period_type' => $validated['period_type'],
            'growth_rate' => $validated['growth_rate'],
            'assumptions' => $validated['assumptions'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('financial-projections.show', $projection)
            ->with('success', 'Proyección financiera creada exitosamente.');
    }

    public function show(FinancialProjection $financialProjection)
    {
        $financialProjection->load('creator');
        return view('financial-projections.show', compact('financialProjection'));
    }

    public function calculate(FinancialProjection $financialProjection, Request $request)
    {
        $historicalData = $request->input('historical_data', []);

        $salesProjection = $financialProjection->projectSales($historicalData);

        return response()->json([
            'sales_projection' => $salesProjection,
        ]);
    }
}
