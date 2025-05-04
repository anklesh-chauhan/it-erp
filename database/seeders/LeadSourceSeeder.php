<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_sources')->insert([
            ['name' => 'Advertisement'],
            ['name' => 'Cold Call'],
            ['name' => 'Employee Referral'],
            ['name' => 'External Referral'],
            ['name' => 'OnlineStore'],
            ['name' => 'Partner'],
            ['name' => 'Public Relations'],
            ['name' => 'Sales Mail Alias'],
            ['name' => 'Seminar Partner'],
            ['name' => 'Seminar-Internal'],
            ['name' => 'Trade Show'],
            ['name' => 'Web Download'],
            ['name' => 'Web Research'],
            ['name' => 'Chat'],
            ['name' => 'X (Twitter)'],
            ['name' => 'Facebook'],
        ]);
    }
}
