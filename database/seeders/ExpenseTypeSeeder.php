<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $expenseTypes = [
            ['name' => 'Daily Allowance',      'code' => 'DAILY_ALLOWANCE'],
            ['name' => 'Travel',               'code' => 'TRAVEL'],
            ['name' => 'Food',                 'code' => 'FOOD'],
            ['name' => 'Accommodation',        'code' => 'ACCOMMODATION'],
            ['name' => 'Fuel',                 'code' => 'FUEL'],
            ['name' => 'Vehicle Maintenance',  'code' => 'VEHICLE_MAINTENANCE'],
            ['name' => 'Client Meeting',       'code' => 'CLIENT_MEETING'],
            ['name' => 'Courier / Delivery',   'code' => 'COURIER'],
            ['name' => 'Office Supplies',      'code' => 'OFFICE_SUPPLIES'],
            ['name' => 'Mobile / Internet',    'code' => 'MOBILE_INTERNET'],
            ['name' => 'Other',                'code' => 'OTHER'],
        ];

        foreach ($expenseTypes as $type) {
            ExpenseType::updateOrCreate(
                ['code' => $type['code']], // unique key
                [
                    'name'      => $type['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
