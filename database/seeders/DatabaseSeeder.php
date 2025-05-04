<?php

namespace Database\Seeders;

use App\Models\AddressType;
use App\Models\DealStage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;
use App\Models\ItemBrand;
use App\Models\TransportMode;
use App\Models\VisitType;
use App\Models\PackingType;
use Illuminate\Support\Facades\DB;
use App\Models\TypeMaster;
use App\Models\Category;
use Carbon\Carbon;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint issues
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables you want to reset
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();
        // DB::table('')->truncate();

        // Enable foreign key checks again
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            // // IndustryTypeSeeder::class,
            // // LeadSourceSeeder::class,
            // // LeadStatusSeeder::class,
            // // RatingTypeSeeder::class,
            FollowUpMediaSeeder::class,
            FollowUpResultSeeder::class,
            FollowUpStatusSeeder::class,
            FollowUpPrioritySeeder::class,
            UnitOfMeasurementSeeder::class,
            ItemBrandSeeder::class,
            TransportModeSeeder::class,
            VisitTypeSeeder::class,
            PackingTypeSeeder::class,
            DealStageSeeder::class,
            TypeMasterSeeder::class,
            CategorySeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

class UnitOfMeasurementSeeder extends Seeder
{
    public function run()
    {
        UnitOfMeasurement::insert([
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Liter', 'abbreviation' => 'L'],
            ['name' => 'Piece', 'abbreviation' => 'pcs'],
        ]);
    }
}

class ItemBrandSeeder extends Seeder
{
    public function run()
    {
        ItemBrand::insert([
            ['name' => 'Brand A'],
            ['name' => 'Brand B'],
            ['name' => 'Brand C'],
        ]);
    }
}

class TransportModeSeeder extends Seeder
{
    public function run()
    {
        TransportMode::insert([
            ['name' => 'Air'],
            ['name' => 'Sea'],
            ['name' => 'Road'],
            ['name' => 'Rail'],
        ]);
    }
}

class VisitTypeSeeder extends Seeder
{
    public function run()
    {
        VisitType::insert([
            ['name' => 'Initial Meeting'],
            ['name' => 'Follow-up'],
            ['name' => 'Technical Discussion'],
            ['name' => 'Final Negotiation'],
        ]);
    }
}

class PackingTypeSeeder extends Seeder
{
    public function run()
    {
        PackingType::insert([
            ['name' => 'Box', 'description' => 'Packaged in a box'],
            ['name' => 'Carton', 'description' => 'Packaged in a carton'],
            ['name' => 'Bag', 'description' => 'Packaged in a bag'],
            ['name' => 'Bottle', 'description' => 'Packaged in a bottle'],
            ['name' => 'Drum', 'description' => 'Packaged in a drum'],
            ['name' => 'Pallet', 'description' => 'Packaged on a pallet'],
        ]);
    }
}

class TypeMasterSeeder extends Seeder
{
    public function run()
    {
        TypeMaster::insert([
            [
                'name' => 'Vendor',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:21:08',
                'updated_at' => '2025-04-11 18:21:08',
            ],
            [
                'name' => 'Customer',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:21:28',
                'updated_at' => '2025-04-11 18:21:28',
            ],
            [
                'name' => 'Dealer',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Transporter',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Manufacturer',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Distributor',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Retailer',
                'description' => null,
                'typeable_type' => 'App\\Models\\AccountMaster',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Billing Address',
                'description' => null,
                'typeable_type' => 'App\\Models\\Address',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Shipping Address',
                'description' => null,
                'typeable_type' => 'App\\Models\\Address',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Office Address',
                'description' => null,
                'typeable_type' => 'App\\Models\\Address',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Warehouse Address',
                'description' => null,
                'typeable_type' => 'App\\Models\\Address',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],
            [
                'name' => 'Delivery Address',
                'description' => null,
                'typeable_type' => 'App\\Models\\Address',
                'typeable_id' => null,
                'created_at' => '2025-04-11 18:52:25',
                'updated_at' => '2025-04-11 18:52:25',
            ],

        ]);
    }
}

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

