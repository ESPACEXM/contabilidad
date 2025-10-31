<?php

namespace App\Http\Controllers;

use App\Models\ChartAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        
        $query = ChartAccount::forTenant($tenantId)->with('parent');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $accounts = $query->orderBy('code')->paginate(20);

        // Cuentas raíz para dropdown
        $rootAccounts = ChartAccount::forTenant($tenantId)
            ->rootAccounts()
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart-accounts.index', compact('accounts', 'rootAccounts'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $rootAccounts = ChartAccount::forTenant($tenantId)
            ->rootAccounts()
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart-accounts.create', compact('rootAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:chart_accounts,id',
            'code' => 'required|string|max:20|unique:chart_accounts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:activo,pasivo,capital,ingreso,egreso',
            'nature' => 'required|in:deudora,acreedora',
            'level' => 'required|in:cuenta_mayor,subcuenta,auxiliar',
            'allows_movements' => 'boolean',
            'is_active' => 'boolean',
            'initial_balance' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        // Validar que el parent pertenezca al mismo tenant
        if ($request->filled('parent_id')) {
            $parent = ChartAccount::find($request->parent_id);
            if ($parent && $parent->tenant_id !== Auth::user()->tenant_id) {
                return back()->withErrors(['parent_id' => 'La cuenta padre seleccionada no es válida.']);
            }
        }

        $validated['tenant_id'] = Auth::user()->tenant_id;

        ChartAccount::create($validated);

        return redirect()->route('chart-accounts.index')
            ->with('success', 'Cuenta contable creada exitosamente.');
    }

    public function show(ChartAccount $chartAccount)
    {
        $this->authorizeTenant($chartAccount);

        $chartAccount->load('parent', 'children');
        
        return view('chart-accounts.show', compact('chartAccount'));
    }

    public function edit(ChartAccount $chartAccount)
    {
        $this->authorizeTenant($chartAccount);

        $tenantId = Auth::user()->tenant_id;
        $rootAccounts = ChartAccount::forTenant($tenantId)
            ->where('id', '!=', $chartAccount->id)
            ->rootAccounts()
            ->active()
            ->orderBy('code')
            ->get();

        return view('chart-accounts.edit', compact('chartAccount', 'rootAccounts'));
    }

    public function update(Request $request, ChartAccount $chartAccount)
    {
        $this->authorizeTenant($chartAccount);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:chart_accounts,id',
            'code' => 'required|string|max:20|unique:chart_accounts,code,' . $chartAccount->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:activo,pasivo,capital,ingreso,egreso',
            'nature' => 'required|in:deudora,acreedora',
            'level' => 'required|in:cuenta_mayor,subcuenta,auxiliar',
            'allows_movements' => 'boolean',
            'is_active' => 'boolean',
            'initial_balance' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        // Validar que el parent pertenezca al mismo tenant y no sea la misma cuenta
        if ($request->filled('parent_id')) {
            $parent = ChartAccount::find($request->parent_id);
            if (!$parent || $parent->tenant_id !== Auth::user()->tenant_id || $parent->id === $chartAccount->id) {
                return back()->withErrors(['parent_id' => 'La cuenta padre seleccionada no es válida.']);
            }
        }

        $chartAccount->update($validated);

        return redirect()->route('chart-accounts.index')
            ->with('success', 'Cuenta contable actualizada exitosamente.');
    }

    public function destroy(ChartAccount $chartAccount)
    {
        $this->authorizeTenant($chartAccount);

        // Verificar si tiene hijos
        if ($chartAccount->hasChildren()) {
            return back()->withErrors(['error' => 'No se puede eliminar una cuenta que tiene sub-cuentas.']);
        }

        $chartAccount->delete();

        return redirect()->route('chart-accounts.index')
            ->with('success', 'Cuenta contable eliminada exitosamente.');
    }

    public function restore($id)
    {
        $chartAccount = ChartAccount::withTrashed()->findOrFail($id);
        $this->authorizeTenant($chartAccount);

        $chartAccount->restore();

        return redirect()->route('chart-accounts.index')
            ->with('success', 'Cuenta contable restaurada exitosamente.');
    }

    protected function authorizeTenant(ChartAccount $chartAccount)
    {
        if ($chartAccount->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }
    }
}

