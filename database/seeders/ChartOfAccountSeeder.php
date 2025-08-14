<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            // Primary Groups
            ['name' => 'Capital Account', 'type' => 'Equity'],
            ['name' => 'Current Assets', 'type' => 'Asset'],
            ['name' => 'Current Liabilities', 'type' => 'Liability'],
            ['name' => 'Direct Expenses', 'type' => 'Expense'],
            ['name' => 'Direct Incomes', 'type' => 'Income'],
            ['name' => 'Indirect Expenses', 'type' => 'Expense'],
            ['name' => 'Indirect Incomes', 'type' => 'Income'],
            ['name' => 'Fixed Assets', 'type' => 'Asset'],
            ['name' => 'Investments', 'type' => 'Asset'],
            ['name' => 'Loans (Liability)', 'type' => 'Liability'],
            ['name' => 'Suspense Account', 'type' => 'Liability'],
            ['name' => 'Miscellaneous Expenses (Asset)', 'type' => 'Asset'],
            ['name' => 'Purchase Account', 'type' => 'Expense'],
            ['name' => 'Sales Account', 'type' => 'Income'],
            ['name' => 'Branch / Divisions', 'type' => 'Asset'],

            // Subgroups under Current Assets
            ['name' => 'Bank Accounts', 'type' => 'Asset', 'parent' => 'Current Assets'],
            ['name' => 'Cash in Hand', 'type' => 'Asset', 'parent' => 'Current Assets'],
            ['name' => 'Deposits', 'type' => 'Asset', 'parent' => 'Current Assets'],
            ['name' => 'Sundry Debtors', 'type' => 'Asset', 'parent' => 'Current Assets'],

            // Subgroups under Current Liabilities
            ['name' => 'Sundry Creditors', 'type' => 'Liability', 'parent' => 'Current Liabilities'],
            ['name' => 'Duties and Taxes', 'type' => 'Liability', 'parent' => 'Current Liabilities'],

            // Subgroups under Capital Account
            ['name' => 'Reserves and Surplus', 'type' => 'Equity', 'parent' => 'Capital Account'],
        ];

        $accountMap = [];

        foreach ($groups as $group) {
            $accountType = AccountType::where('name', $group['type'])->first();

            $parentId = null;
            if (isset($group['parent'])) {
                $parentId = $accountMap[$group['parent']] ?? ChartOfAccount::where('name', $group['parent'])->value('id');
            }

            $coa = ChartOfAccount::firstOrCreate(
                ['name' => $group['name']],
                [
                    'account_type_id' => $accountType->id,
                    'parent_id' => $parentId,
                    'is_group' => true,
                    'is_active' => true,
                    'is_system' => true,
                    'code' => strtoupper(substr($group['name'], 0, 3)) . '-' . uniqid(),
                ]
            );

            $accountMap[$group['name']] = $coa->id;
        }
    }
}
