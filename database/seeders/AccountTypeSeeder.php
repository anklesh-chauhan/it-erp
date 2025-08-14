<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountType;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Asset',     'code' => 'A', 'description' => 'Resources owned by the business'],
            ['name' => 'Liability', 'code' => 'L', 'description' => 'Obligations or debts'],
            ['name' => 'Income',    'code' => 'I', 'description' => 'Revenue earned'],
            ['name' => 'Expense',   'code' => 'E', 'description' => 'Costs incurred'],
            ['name' => 'Equity',    'code' => 'Q', 'description' => 'Owner’s interest'],
        ];

        foreach ($types as $type) {
            AccountType::firstOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'is_active' => true,
                    'is_system' => true,
                ]
            );
        }
    }
}
