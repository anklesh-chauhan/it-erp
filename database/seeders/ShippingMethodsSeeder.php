<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipping_methods')->insert([
            [
                'name' => 'FedEx',
                'description' => 'International and domestic courier services by FedEx.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DHL',
                'description' => 'Worldwide express and logistics shipping by DHL.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UPS',
                'description' => 'United Parcel Service for domestic and international shipments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'USPS',
                'description' => 'United States Postal Service standard and priority shipping.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blue Dart',
                'description' => 'Domestic courier and logistics service in India.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'India Post',
                'description' => 'Government postal service for domestic and international shipping.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Local Pickup',
                'description' => 'Customer picks up the order directly from the store/warehouse.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Own Vehicle Delivery',
                'description' => 'In-house delivery using company-owned vehicles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
