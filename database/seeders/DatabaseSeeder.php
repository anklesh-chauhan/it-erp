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
use App\Models\CityPinCode;
use App\Models\EmpGrade;
use App\Models\EmpJobTitle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tax;
use App\Models\TaxComponent;
use Faker\Provider\ar_EG\Payment;

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
        // DB::table('employee_attendance_statuses')->truncate();
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

        try {
            Artisan::call('shield:generate --all');
            $this->command->info('Filament Shield permissions generated successfully.');
        } catch (\Exception $e) {
            $this->command->error('Failed to run shield:generate --all: ' . $e->getMessage());
        }

        $this->call([
            UsersTableSeeder::class,
            ModelHasRolesSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            CityPinCodeSeeder::class,
            IndustryTypeSeeder::class,
            LeadSourceSeeder::class,
            LeadStatusSeeder::class,
            RatingTypeSeeder::class,
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
            EmpDepartmentSeeder::class,
            EmpGradeSeeder::class,
            EmpJobTitleSeeder::class,
            AccountTypeSeeder::class,
            ChartOfAccountSeeder::class,
            OrganizationSeeder::class,
            TaxSeeder::class,
            TaxComponentSeeder::class,
            SalesDocumentPreferenceSeeder::class,
            PaymentTermsSeeder::class,
            PaymentMethodsSeeder::class,
            ShippingMethodsSeeder::class,
            AddressTypeSeeder::class,
            EmployeeAttendanceStatusSeeder::class,
            DepartmentRoleSeeder::class,
        ]);
    }
}

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAssignments = [
            [
                'role_id' => 1, // Super-admin role (e.g., from filament-shield)
                'model_type' => 'App\\Models\\User',
                'model_id' => 1, // Admin user
            ],
        ];

        // Insert role assignments into the model_has_roles table
        DB::table('model_has_roles')->insert($roleAssignments);
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
                'name' => 'Potential Customer',
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

            [
                'name' => 'New Business',
                'description' => 'Acquiring a new customer with no prior relationship',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Upsell',
                'description' => 'Selling additional features or higher-tier plans to an existing customer',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cross-sell',
                'description' => 'Offering complementary products or services to an existing customer',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Renewal',
                'description' => 'Extending an existing contract or subscription with a current customer',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Expansion',
                'description' => 'Increasing the scope of services with an existing customer across new departments or locations',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Replacement',
                'description' => 'Converting a customer from a competitor’s product to yours',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Referral',
                'description' => 'Deals originating from recommendations by existing customers or partners',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'One-Time Sale',
                'description' => 'Non-recurring purchases by a customer',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Recurring Revenue',
                'description' => 'Deals generating ongoing revenue, such as subscriptions or retainers',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Strategic Partnership',
                'description' => 'Long-term deals involving collaboration or co-development with a client',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Project-Based',
                'description' => 'Deals based on specific projects with defined scopes and timelines',
                'typeable_type' => 'App\\Models\\Deal',
                'typeable_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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

class EmpDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'department_name' => 'Sales',
                'department_code' => 'SALES',
                'description' => 'Handles sales operations and client acquisition',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => 'Primary revenue-generating department',
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Marketing',
                'department_code' => 'MKT',
                'description' => 'Manages marketing campaigns and brand promotion',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Human Resources',
                'department_code' => 'HR',
                'description' => 'Oversees recruitment, training, and employee relations',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => 'Handles employee onboarding and compliance',
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Information Technology',
                'department_code' => 'IT',
                'description' => 'Manages IT infrastructure and software development',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Finance',
                'department_code' => 'FIN',
                'description' => 'Handles budgeting, accounting, and financial reporting',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => 'Ensures financial compliance and reporting',
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Operations',
                'department_code' => 'OPS',
                'description' => 'Manages day-to-day operational activities and logistics',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Customer Support',
                'department_code' => 'CS',
                'description' => 'Provides support and assistance to customers',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => 'Handles customer queries and complaints',
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Research and Development',
                'department_code' => 'RND',
                'description' => 'Focuses on product innovation and development',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Legal',
                'department_code' => 'LGL',
                'description' => 'Manages legal compliance and contracts',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => 'Oversees legal agreements and disputes',
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Administration',
                'department_code' => 'ADM',
                'description' => 'Handles administrative tasks and office management',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'department_name' => 'Production',
                'department_code' => 'PROD',
                'description' => 'Handles production tasks',
                // 'organizational_unit_id' => 1,
                'created_by_user_id' => 1,
                'updated_by_user_id' => 1,
                'deleted_by_user_id' => null,
                'is_active' => true,
                'is_deleted' => false,
                'remark' => null,
                'department_head_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert departments into the emp_departments table
        DB::table('emp_departments')->insert($departments);
    }
}

class EmpGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [
            [
                'grade_name' => 'Sales Representative',
                'description' => 'Entry-level sales role focused on client acquisition',
                // 'department_id' => 1, // Sales department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Sales Manager',
                'description' => 'Manages sales team and client relationships',
                // 'department_id' => 1, // Sales department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Marketing Coordinator',
                'description' => 'Supports marketing campaigns and content creation',
                // 'department_id' => 2, // Marketing department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Marketing Manager',
                'description' => 'Oversees marketing strategies and team performance',
                // 'department_id' => 2, // Marketing department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'HR Specialist',
                'description' => 'Handles recruitment and employee onboarding',
                // 'department_id' => 3, // Human Resources department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'HR Director',
                'description' => 'Leads HR strategy and compliance',
                // 'department_id' => 3, // Human Resources department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'IT Support Technician',
                'description' => 'Provides technical support and system maintenance',
                // 'department_id' => 4, // Information Technology department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Senior Developer',
                'description' => 'Leads software development projects',
                // 'department_id' => 4, // Information Technology department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Accountant',
                'description' => 'Manages financial records and reporting',
                // 'department_id' => 5, // Finance department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Financial Analyst',
                'description' => 'Analyzes financial data and forecasts',
                // 'department_id' => 5, // Finance department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Operations Coordinator',
                'description' => 'Supports operational workflows and logistics',
                // 'department_id' => 6, // Operations department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Operations Manager',
                'description' => 'Oversees operational efficiency and processes',
                // 'department_id' => 6, // Operations department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Customer Support Representative',
                'description' => 'Handles customer inquiries and issues',
                // 'department_id' => 7, // Customer Support department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Customer Success Manager',
                'description' => 'Ensures customer satisfaction and retention',
                // 'department_id' => 7, // Customer Support department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'R&D Engineer',
                'description' => 'Conducts research and develops new products',
                // 'department_id' => 8, // Research and Development department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'R&D Manager',
                'description' => 'Leads research and development initiatives',
                // 'department_id' => 8, // Research and Development department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Legal Assistant',
                'description' => 'Supports legal documentation and compliance',
                // 'department_id' => 9, // Legal department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'General Counsel',
                'description' => 'Oversees legal strategy and compliance',
                // 'department_id' => 9, // Legal department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Administrative Assistant',
                'description' => 'Handles office administration and support tasks',
                // 'department_id' => 10, // Administration department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'grade_name' => 'Office Manager',
                'description' => 'Manages office operations and staff',
                // 'department_id' => 10, // Administration department
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert grades into the emp_grades table
        DB::table('emp_grades')->insert($grades);
    }
}

class EmpJobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobTitles = [
            [
                'title' => 'Sales Executive',
                'description' => 'Drives sales through client outreach and deal negotiations',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Marketing Specialist',
                'description' => 'Develops and executes marketing campaigns',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'HR Manager',
                'description' => 'Oversees recruitment, training, and employee relations',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Software Engineer',
                'description' => 'Designs and develops software applications',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Accountant',
                'description' => 'Manages financial records and ensures compliance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Operations Supervisor',
                'description' => 'Coordinates operational activities and logistics',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Customer Support Agent',
                'description' => 'Assists customers with inquiries and issues',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'R&D Scientist',
                'description' => 'Conducts research to innovate new products',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Legal Counsel',
                'description' => 'Provides legal advice and manages contracts',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Administrative Coordinator',
                'description' => 'Supports office operations and administrative tasks',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert job titles into the emp_job_titles table
        DB::table('emp_job_titles')->insert($jobTitles);
    }
}
