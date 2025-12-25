<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            // Top-level categories
            [
                'name' => 'Inventory',
                'alias' => 'INV',
                'parent_id' => null,
                'description' => 'Categories related to inventory management',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\ItemMaster',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:55:56'),
                'updated_at' => Carbon::parse('2025-04-11 18:55:56'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Sales',
                'alias' => 'SLS',
                'parent_id' => null,
                'description' => 'Categories related to sales and orders',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\Deal',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:00'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:00'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Procurement',
                'alias' => 'PRC',
                'parent_id' => null,
                'description' => 'Categories related to procurement and purchasing',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\AccountMaster',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:05'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:05'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Finance',
                'alias' => 'FIN',
                'parent_id' => null,
                'description' => 'Categories related to financial management',
                'image_path' => null,
                'modelable_type' => null,
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:10'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:10'),
                'deleted_at' => null,
            ],

            // Subcategories
            [
                'name' => 'Raw Materials',
                'alias' => 'RM',
                'parent_id' => 1, // Inventory
                'description' => 'Raw materials for production',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\ItemMaster',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:15'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:15'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Finished Goods',
                'alias' => 'FG',
                'parent_id' => 1, // Inventory
                'description' => 'Completed products ready for sale',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\ItemMaster',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:20'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:20'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Orders',
                'alias' => 'ORD',
                'parent_id' => 2, // Sales
                'description' => 'Customer orders and quotations',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\Deal',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:25'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:25'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Vendors',
                'alias' => 'VEN',
                'parent_id' => 3, // Procurement
                'description' => 'Vendor-related procurement categories',
                'image_path' => null,
                'modelable_type' => 'App\\Models\\AccountMaster',
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:30'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:30'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Accounts Payable',
                'alias' => 'AP',
                'parent_id' => 4, // Finance
                'description' => 'Accounts payable transactions',
                'image_path' => null,
                'modelable_type' => null,
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:35'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:35'),
                'deleted_at' => null,
            ],
            [
                'name' => 'Accounts Receivable',
                'alias' => 'AR',
                'parent_id' => 4, // Finance
                'description' => 'Accounts receivable transactions',
                'image_path' => null,
                'modelable_type' => null,
                'modelable_id' => null,
                'created_at' => Carbon::parse('2025-04-11 18:56:40'),
                'updated_at' => Carbon::parse('2025-04-11 18:56:40'),
                'deleted_at' => null,
            ],
        ];

        Category::insert($categories);
    }
}
