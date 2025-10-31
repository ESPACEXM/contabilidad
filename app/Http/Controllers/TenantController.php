<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->paginate(15);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rfc' => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:255',
            'currency' => 'required|string|size:3',
        ]);

        $tenant = Tenant::create([
            ...$validated,
            'slug' => \Str::slug($validated['name']) . '-' . \Str::random(5),
            'is_active' => true,
        ]);

        return redirect()->route('tenants.show', $tenant)
            ->with('success', 'Empresa creada exitosamente.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('users', 'chartAccounts');
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rfc' => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:255',
            'currency' => 'required|string|size:3',
            'is_active' => 'boolean',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.show', $tenant)
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('tenants.index')
            ->with('success', 'Empresa eliminada exitosamente.');
    }
}

