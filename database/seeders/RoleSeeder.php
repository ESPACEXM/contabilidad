<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'view chart accounts',
            'create chart accounts',
            'edit chart accounts',
            'delete chart accounts',
            'view tenants',
            'create tenants',
            'edit tenants',
            'delete tenants',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'administrador']);
        $accountant = Role::firstOrCreate(['name' => 'contador']);
        $employee = Role::firstOrCreate(['name' => 'empleado']);

        // Asignar permisos a roles
        $superAdmin->givePermissionTo(Permission::all());
        
        $admin->givePermissionTo([
            'view chart accounts',
            'create chart accounts',
            'edit chart accounts',
            'delete chart accounts',
        ]);

        $accountant->givePermissionTo([
            'view chart accounts',
            'create chart accounts',
            'edit chart accounts',
        ]);

        $employee->givePermissionTo([
            'view chart accounts',
        ]);
    }
}

