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
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Administrador Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $admin->assignRole('administrador');

        // Crear otro tenant de ejemplo
        $tenant2 = Tenant::create([
            'name' => 'Mi Negocio S.A.',
            'slug' => 'mi-negocio-' . Str::random(5),
            'email' => 'contacto@minegocio.com',
            'currency' => 'MXN',
            'is_active' => true,
        ]);

        $admin2 = User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Juan Pérez',
            'email' => 'juan@minegocio.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $admin2->assignRole('administrador');
    }
}

