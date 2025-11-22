<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Empresa Demo S.A.',
            'slug' => 'empresa-demo-' . Str::random(5),
            'email' => 'demo@empresa.com',
            'phone' => '+52 555 123 4567',
            'address' => 'Av. Principal 123, Ciudad de México',
            'rfc' => 'DEM123456ABC',
            'razon_social' => 'Empresa Demo Sociedad Anónima',
            'currency' => 'MXN',
            'is_active' => true,
            ]
        );

        // Crear usuario admin solo si no existe
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Administrador Demo',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Asegurar que tenga el rol
        if (!$admin->hasRole('administrador')) {
            $admin->assignRole('administrador');
        }

        // Crear otro tenant de ejemplo (opcional, solo si no existe)
        $tenant2 = Tenant::firstOrCreate(
            ['email' => 'contacto@minegocio.com'],
            [
            'name' => 'Mi Negocio S.A.',
            'slug' => 'mi-negocio-' . Str::random(5),
            'email' => 'contacto@minegocio.com',
            'currency' => 'MXN',
            'is_active' => true,
            ]
        );

        // Crear usuario admin2 solo si no existe
        $admin2 = User::firstOrCreate(
            ['email' => 'juan@minegocio.com'],
            [
                'tenant_id' => $tenant2->id,
                'name' => 'Juan Pérez',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Asegurar que tenga el rol
        if (!$admin2->hasRole('administrador')) {
            $admin2->assignRole('administrador');
        }
    }
}

