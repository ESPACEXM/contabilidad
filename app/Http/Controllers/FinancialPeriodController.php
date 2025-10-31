<?php

namespace App\Http\Controllers;

use App\Models\FinancialPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinancialPeriodController extends Controller
{
    public function index()
    {
        $periods = FinancialPeriod::forTenant(Auth::user()->tenant_id)
            ->with(['budgets', 'closer'])
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('financial-periods.index', compact('periods'));
    }

    public function create()
    {
        return view('financial-periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Verificar que no se solapen los periodos
        $overlapping = FinancialPeriod::forTenant(Auth::user()->tenant_id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['period' => 'El periodo se solapa con otro periodo existente.']);
        }

        $period = FinancialPeriod::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'period_type' => $validated['period_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('financial-periods.index')
            ->with('success', 'Periodo financiero creado exitosamente.');
    }

    public function show(FinancialPeriod $financialPeriod)
    {
        $financialPeriod->load(['budgets.items', 'closer']);
        return view('financial-periods.show', compact('financialPeriod'));
    }

    public function edit(FinancialPeriod $financialPeriod)
    {
        if ($financialPeriod->is_closed) {
            return redirect()->route('financial-periods.index')
                ->with('error', 'No se puede editar un periodo cerrado.');
        }

        return view('financial-periods.edit', compact('financialPeriod'));
    }

    public function update(Request $request, FinancialPeriod $financialPeriod)
    {
        if ($financialPeriod->is_closed) {
            return redirect()->route('financial-periods.index')
                ->with('error', 'No se puede editar un periodo cerrado.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $financialPeriod->update($validated);

        return redirect()->route('financial-periods.index')
            ->with('success', 'Periodo financiero actualizado exitosamente.');
    }

    public function destroy(FinancialPeriod $financialPeriod)
    {
        if ($financialPeriod->is_closed) {
            return redirect()->route('financial-periods.index')
                ->with('error', 'No se puede eliminar un periodo cerrado.');
        }

        if ($financialPeriod->budgets()->count() > 0) {
            return redirect()->route('financial-periods.index')
                ->with('error', 'No se puede eliminar un periodo que tiene presupuestos asociados.');
        }

        $financialPeriod->delete();
        return redirect()->route('financial-periods.index')
            ->with('success', 'Periodo financiero eliminado exitosamente.');
    }

    public function close(FinancialPeriod $financialPeriod)
    {
        if ($financialPeriod->is_closed) {
            return redirect()->route('financial-periods.index')
                ->with('error', 'El periodo ya está cerrado.');
        }

        $financialPeriod->close(Auth::id());

        return redirect()->route('financial-periods.index')
            ->with('success', 'Periodo financiero cerrado exitosamente.');
    }
}
