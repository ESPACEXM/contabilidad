<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $tenant = $user->tenant;

            if (!$tenant || !$tenant->isActive()) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['message' => 'Tu empresa no está activa o ha sido eliminada.']);
            }

            // Agregar tenant al request para acceso global
            $request->merge(['current_tenant' => $tenant]);
            app()->instance('current_tenant', $tenant);
        }

        return $next($request);
    }
}

