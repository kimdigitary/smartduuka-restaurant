<?php

namespace Database\Seeders;

use App\Enums\Role as EnumRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::find(EnumRole::ADMIN);
        $superAdminRole = Role::find(EnumRole::SUPER_ADMIN);
        $superAdminRole?->givePermissionTo(Permission::all());
        $adminRole?->givePermissionTo(Permission::wherenotin('name', ['branches_create'])->get());

        $branchManager = Role::find(5);
        if ($branchManager) {
            $branchManagerPermissions = [
                ['name' => 'dashboard'],
                ['name' => 'dining-tables'],
                ['name' => 'pos'],
                ['name' => 'pos-orders'],

                ['name' => 'expenses'],
                ['name' => 'expenses_create'],
                ['name' => 'expenses_edit'],
                ['name' => 'expenses_delete'],
                ['name' => 'expenses_show'],

                ['name' => 'online-orders'],
                ['name' => 'table-orders'],
                ['name' => 'push-notifications'],
                ['name' => 'push-notifications_create'],
                ['name' => 'push-notifications_edit'],
                ['name' => 'push-notifications_delete'],
                ['name' => 'push-notifications_show'],
                ['name' => 'messages'],
                ['name' => 'delivery-boys'],
                ['name' => 'delivery-boys_create'],
                ['name' => 'delivery-boys_edit'],
                ['name' => 'delivery-boys_delete'],
                ['name' => 'delivery-boys_show'],
                ['name' => 'customers'],
                ['name' => 'customers_create'],
                ['name' => 'customers_edit'],
                ['name' => 'customers_delete'],
                ['name' => 'customers_show'],
                ['name' => 'employees'],
                ['name' => 'employees_create'],
                ['name' => 'employees_edit'],
                ['name' => 'employees_delete'],
                ['name' => 'employees_show'],
                ['name' => 'transactions'],
                ['name' => 'sales-report']
            ];
            $branchManagerPermissions = Permission::whereIn('name', $branchManagerPermissions)->get();
            $branchManager->givePermissionTo($branchManagerPermissions);
        }

        $posOperatorManager = Role::find(6);
        if ($posOperatorManager) {
            $posOperatorManagerPermissions = [
                ['name' => 'dashboard'],
                ['name' => 'pos'],
                ['name' => 'pos-orders']
            ];
            $posOperatorManagerPermissions = Permission::whereIn('name', $posOperatorManagerPermissions)->get();
            $posOperatorManager->givePermissionTo($posOperatorManagerPermissions);
        }
    }
}
