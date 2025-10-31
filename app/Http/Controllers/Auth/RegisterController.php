<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'tenant_name' => 'required|string|max:255',
            'tenant_email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Crear tenant
        $tenant = Tenant::create([
            'name' => $request->tenant_name,
            'slug' => Str::slug($request->tenant_name) . '-' . Str::random(5),
            'email' => $request->tenant_email,
            'is_active' => true,
        ]);

        // Crear usuario administrador
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Asignar rol de administrador
        $user->assignRole('administrador');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}

