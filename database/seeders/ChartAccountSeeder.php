<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\ChartAccount;
use Illuminate\Database\Seeder;

class ChartAccountSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->createChartAccounts($tenant);
        }
    }

    private function createChartAccounts(Tenant $tenant)
    {
        // Activo
        $activo = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '1'],
            [
                'name' => 'Activo',
                'type' => 'activo',
                'nature' => 'deudora',
                'level' => 'cuenta_mayor',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 1,
            ]
        );

        $activoCirculante = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '1.1'],
            [
                'parent_id' => $activo->id,
                'name' => 'Activo Circulante',
                'type' => 'activo',
                'nature' => 'deudora',
                'level' => 'subcuenta',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 1,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '1.1.01'],
            [
                'parent_id' => $activoCirculante->id,
                'name' => 'Caja',
                'type' => 'activo',
                'nature' => 'deudora',
                'level' => 'auxiliar',
                'allows_movements' => true,
                'is_active' => true,
                'initial_balance' => 50000.00,
                'order' => 1,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '1.1.02'],
            [
                'parent_id' => $activoCirculante->id,
                'name' => 'Bancos',
                'type' => 'activo',
                'nature' => 'deudora',
                'level' => 'auxiliar',
                'allows_movements' => true,
                'is_active' => true,
                'initial_balance' => 150000.00,
                'order' => 2,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '1.1.03'],
            [
                'parent_id' => $activoCirculante->id,
                'name' => 'Clientes',
                'type' => 'activo',
                'nature' => 'deudora',
                'level' => 'auxiliar',
                'allows_movements' => true,
                'is_active' => true,
                'initial_balance' => 75000.00,
                'order' => 3,
            ]
        );

        // Pasivo
        $pasivo = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '2'],
            [
                'name' => 'Pasivo',
                'type' => 'pasivo',
                'nature' => 'acreedora',
                'level' => 'cuenta_mayor',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 2,
            ]
        );

        $pasivoCirculante = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '2.1'],
            [
                'parent_id' => $pasivo->id,
                'name' => 'Pasivo Circulante',
                'type' => 'pasivo',
                'nature' => 'acreedora',
                'level' => 'subcuenta',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 1,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '2.1.01'],
            [
                'parent_id' => $pasivoCirculante->id,
                'name' => 'Proveedores',
                'type' => 'pasivo',
                'nature' => 'acreedora',
                'level' => 'auxiliar',
                'allows_movements' => true,
                'is_active' => true,
                'initial_balance' => 45000.00,
                'order' => 1,
            ]
        );

        // Capital
        $capital = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '3'],
            [
                'name' => 'Capital',
                'type' => 'capital',
                'nature' => 'acreedora',
                'level' => 'cuenta_mayor',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 3,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '3.1'],
            [
                'parent_id' => $capital->id,
                'name' => 'Capital Social',
                'type' => 'capital',
                'nature' => 'acreedora',
                'level' => 'subcuenta',
                'allows_movements' => true,
                'is_active' => true,
                'initial_balance' => 200000.00,
                'order' => 1,
            ]
        );

        // Ingresos
        $ingresos = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '4'],
            [
                'name' => 'Ingresos',
                'type' => 'ingreso',
                'nature' => 'acreedora',
                'level' => 'cuenta_mayor',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 4,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '4.1'],
            [
                'parent_id' => $ingresos->id,
                'name' => 'Ventas',
                'type' => 'ingreso',
                'nature' => 'acreedora',
                'level' => 'subcuenta',
                'allows_movements' => true,
                'is_active' => true,
                'order' => 1,
            ]
        );

        // Egresos
        $egresos = ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '5'],
            [
                'name' => 'Egresos',
                'type' => 'egreso',
                'nature' => 'deudora',
                'level' => 'cuenta_mayor',
                'allows_movements' => false,
                'is_active' => true,
                'order' => 5,
            ]
        );

        ChartAccount::firstOrCreate(
            ['tenant_id' => $tenant->id, 'code' => '5.1'],
            [
                'parent_id' => $egresos->id,
                'name' => 'Gastos de Operación',
                'type' => 'egreso',
                'nature' => 'deudora',
                'level' => 'subcuenta',
                'allows_movements' => true,
                'is_active' => true,
                'order' => 1,
            ]
        );
    }
}

