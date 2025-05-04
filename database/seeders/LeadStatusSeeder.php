<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_statuses')->insert([
            ['name' => 'Attempted to Contact'],
            ['name' => 'Contact in Future'],
            ['name' => 'Contacted'],
            ['name' => 'Junk Lead'],
            ['name' => 'Lost Lead'],
            ['name' => 'Not Contacted'],
            ['name' => 'Pre-Qualified'],
            ['name' => 'Not Qualified'],
            ['name' => 'Converted'],
        ]);
    }
}
