<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states')->insert([
            ['name' => 'ANDAMAN & NICOBAR ISLANDS', 'country_id' => 1],
            ['name' => 'ANDHRA PRADESH', 'country_id' => 1],
            ['name' => 'ARUNACHAL PRADESH', 'country_id' => 1],
            ['name' => 'ASSAM', 'country_id' => 1],
            ['name' => 'BIHAR', 'country_id' => 1],
            ['name' => 'CHANDIGARH', 'country_id' => 1],
            ['name' => 'CHATTISGARH', 'country_id' => 1],
            ['name' => 'DADRA & NAGAR HAVELI', 'country_id' => 1],
            ['name' => 'DAMAN & DIU', 'country_id' => 1],
            ['name' => 'DELHI', 'country_id' => 1],
            ['name' => 'GOA', 'country_id' => 1],
            ['name' => 'GUJARAT', 'country_id' => 1],
            ['name' => 'HARYANA', 'country_id' => 1],
            ['name' => 'HIMACHAL PRADESH', 'country_id' => 1],
            ['name' => 'JAMMU & KASHMIR', 'country_id' => 1],
            ['name' => 'JHARKHAND', 'country_id' => 1],
            ['name' => 'KARNATAKA', 'country_id' => 1],
            ['name' => 'KERALA', 'country_id' => 1],
            ['name' => 'LAKSHADWEEP', 'country_id' => 1],
            ['name' => 'MADHYA PRADESH', 'country_id' => 1],
            ['name' => 'MAHARASHTRA', 'country_id' => 1],
            ['name' => 'MANIPUR', 'country_id' => 1],
            ['name' => 'MEGHALAYA', 'country_id' => 1],
            ['name' => 'MIZORAM', 'country_id' => 1],
            ['name' => 'NAGALAND', 'country_id' => 1],
            ['name' => 'NULL', 'country_id' => 1],
            ['name' => 'ODISHA', 'country_id' => 1],
            ['name' => 'PONDICHERRY', 'country_id' => 1],
            ['name' => 'PUNJAB', 'country_id' => 1],
            ['name' => 'RAJASTHAN', 'country_id' => 1],
            ['name' => 'SIKKIM', 'country_id' => 1],
            ['name' => 'TAMIL NADU', 'country_id' => 1],
            ['name' => 'TELANGANA', 'country_id' => 1],
            ['name' => 'TRIPURA', 'country_id' => 1],
            ['name' => 'UTTAR PRADESH', 'country_id' => 1],
            ['name' => 'UTTARAKHAND', 'country_id' => 1],
            ['name' => 'WEST BENGAL', 'country_id' => 1],
        ]);
    }
}
