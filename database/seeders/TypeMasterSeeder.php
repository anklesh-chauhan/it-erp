<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeMaster;
use Illuminate\Support\Carbon;

class TypeMasterSeeder extends Seeder
{
    public function run()
    {
        /* =====================================================
         | ACCOUNT MASTER TYPES
         ===================================================== */

        $customer = TypeMaster::factory()
            ->accountRoot()
            ->create(['name' => 'Customer']);

        $vendor = TypeMaster::factory()
            ->accountRoot()
            ->create(['name' => 'Vendor']);

        $dealer = TypeMaster::factory()
            ->accountRoot()
            ->create(['name' => 'Dealer']);

        $transporter = TypeMaster::factory()
            ->accountRoot()
            ->create(['name' => 'Transporter']);

        // Account sub-types
        TypeMaster::factory()->subType($customer)->create(['name' => 'Retail Customer']);
        TypeMaster::factory()->subType($customer)->create(['name' => 'Corporate Customer']);
        TypeMaster::factory()->subType($customer)->create(['name' => 'Government Customer']);

        TypeMaster::factory()->subType($vendor)->create(['name' => 'Manufacturer Vendor']);
        TypeMaster::factory()->subType($vendor)->create(['name' => 'Trader Vendor']);
        TypeMaster::factory()->subType($vendor)->create(['name' => 'Service Vendor']);

        TypeMaster::factory()->subType($dealer)->create(['name' => 'Authorized Dealer']);
        TypeMaster::factory()->subType($dealer)->create(['name' => 'Channel Partner']);

        TypeMaster::factory()->subType($transporter)->create(['name' => 'Fleet Transporter']);
        TypeMaster::factory()->subType($transporter)->create(['name' => 'Individual Transporter']);

        /* =====================================================
         | ADDRESS TYPES
         ===================================================== */

        $office = TypeMaster::factory()
            ->addressRoot()
            ->create(['name' => 'Office Address']);

        TypeMaster::factory()->subType($office)->create(['name' => 'Registered Office']);
        TypeMaster::factory()->subType($office)->create(['name' => 'Head Office']);

        /* =====================================================
         | DEAL TYPES
         ===================================================== */

        $recurring = TypeMaster::factory()
            ->dealRoot()
            ->create(['name' => 'Recurring Revenue']);

        TypeMaster::factory()->subType($recurring)->create(['name' => 'AMC Deal']);
        TypeMaster::factory()->subType($recurring)->create(['name' => 'Subscription Deal']);

        /* =====================================================
         | LOCATION TYPES
         ===================================================== */

        $branch = TypeMaster::factory()
            ->locationRoot()
            ->create(['name' => 'Branch']);

        $warehouse = TypeMaster::factory()
            ->locationRoot()
            ->create(['name' => 'Warehouse']);

        TypeMaster::factory()->subType($branch)->create(['name' => 'Regional Branch']);
        TypeMaster::factory()->subType($warehouse)->create(['name' => 'Cold Storage Warehouse']);

    }
}
