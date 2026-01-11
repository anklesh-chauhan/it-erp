<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaveRuleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'validation',
            'workflow',
            'notification',
            'computation',
            'restriction',
            'visibility',
            'payroll',
        ];

        foreach ($categories as $key) {
            DB::table('leave_rule_categories')->updateOrInsert(
                ['key' => $key],
                [
                    'key'         => $key,
                    'name'        => Str::title($key),
                    'description' => ucfirst($key) . ' related leave rule configuration.',
                ]
            );
        }
    }
}
