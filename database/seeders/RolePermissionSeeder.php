<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view_leads']);
        Permission::create(['name' => 'manage_orders']);
        Permission::create(['name' => 'access_reports']);

        // Roles
        $admin = Role::create(['name' => 'admin']);
        $salesRep = Role::create(['name' => 'sales_rep']);
        $inventoryManager = Role::create(['name' => 'inventory_manager']);

        // Assign Permissions
        $admin->givePermissionTo(['view_leads', 'manage_orders', 'access_reports']);
        $salesRep->givePermissionTo('view_leads');
        $inventoryManager->givePermissionTo('manage_orders');
    }
}
