<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('industry_types')->insert([
            ['name' => 'ASP (Application Service Provider)'],
            ['name' => 'Data/Telecom OEM'],
            ['name' => 'ERP (Enterprise Resource Planning)'],
            ['name' => 'Government/Military'],
            ['name' => 'Large Enterprise'],
            ['name' => 'Management'],
            ['name' => 'ISVMSP (Management Service Provider)'],
            ['name' => 'Network Equipment (Enterprise)'],
            ['name' => 'Non-management ISV'],
            ['name' => 'Optical Networking'],
            ['name' => 'Service Provider'],
            ['name' => 'Small/Medium Enterprise'],
            ['name' => 'Storage Equipment'],
            ['name' => 'Storage Service Provider'],
            ['name' => 'Systems Integrator'],
            ['name' => 'Wireless Industry'],
        ]);
    }
}
