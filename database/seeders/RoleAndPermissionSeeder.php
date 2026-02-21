<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-procurement', 'approve-requisitions', 'approve-pos', 'approve-invoices',
            'manage-vendors', 'approve-vendors', 'view-vendors',
            'manage-inventory', 'manage-warehouses', 'warehouse-operations',
            'manage-fleet', 'view-fleet',
            'view-reports', 'manage-budgets',
            'supplier-portal', 'manage-users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin' => $permissions,
            'manager' => ['manage-procurement','approve-requisitions','approve-pos','approve-invoices','manage-vendors','approve-vendors','view-vendors','manage-inventory','manage-warehouses','warehouse-operations','view-fleet','view-reports','manage-budgets'],
            'buyer' => ['manage-procurement','view-vendors','manage-inventory','view-reports'],
            'warehouse_worker' => ['warehouse-operations','manage-inventory'],
            'supplier' => ['supplier-portal'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
