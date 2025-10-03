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
            // Format: ['name' => 'STATE_NAME', 'country_id' => 1, 'gst_code' => 'XX']
            ['name' => 'ANDAMAN & NICOBAR ISLANDS', 'country_id' => 1, 'gst_code' => '35'],
            ['name' => 'ANDHRA PRADESH', 'country_id' => 1, 'gst_code' => '37'],
            ['name' => 'ARUNACHAL PRADESH', 'country_id' => 1, 'gst_code' => '12'],
            ['name' => 'ASSAM', 'country_id' => 1, 'gst_code' => '18'],
            ['name' => 'BIHAR', 'country_id' => 1, 'gst_code' => '10'],
            ['name' => 'CHANDIGARH', 'country_id' => 1, 'gst_code' => '04'],
            ['name' => 'CHATTISGARH', 'country_id' => 1, 'gst_code' => '22'],
            ['name' => 'DADRA & NAGAR HAVELI', 'country_id' => 1, 'gst_code' => '26'],
            ['name' => 'DAMAN & DIU', 'country_id' => 1, 'gst_code' => '25'],
            ['name' => 'DELHI', 'country_id' => 1, 'gst_code' => '07'],
            ['name' => 'GOA', 'country_id' => 1, 'gst_code' => '30'],
            ['name' => 'GUJARAT', 'country_id' => 1, 'gst_code' => '24'],
            ['name' => 'HARYANA', 'country_id' => 1, 'gst_code' => '06'],
            ['name' => 'HIMACHAL PRADESH', 'country_id' => 1, 'gst_code' => '02'],
            ['name' => 'JAMMU & KASHMIR', 'country_id' => 1, 'gst_code' => '01'],
            ['name' => 'JHARKHAND', 'country_id' => 1, 'gst_code' => '20'],
            ['name' => 'KARNATAKA', 'country_id' => 1, 'gst_code' => '29'],
            ['name' => 'KERALA', 'country_id' => 1, 'gst_code' => '32'],
            ['name' => 'LAKSHADWEEP', 'country_id' => 1, 'gst_code' => '31'],
            ['name' => 'MADHYA PRADESH', 'country_id' => 1, 'gst_code' => '23'],
            ['name' => 'MAHARASHTRA', 'country_id' => 1, 'gst_code' => '27'],
            ['name' => 'MANIPUR', 'country_id' => 1, 'gst_code' => '14'],
            ['name' => 'MEGHALAYA', 'country_id' => 1, 'gst_code' => '17'],
            ['name' => 'MIZORAM', 'country_id' => 1, 'gst_code' => '15'],
            ['name' => 'NAGALAND', 'country_id' => 1, 'gst_code' => '13'],
            ['name' => 'NULL', 'country_id' => 1, 'gst_code' => '00'], // Placeholder/Invalid code
            ['name' => 'ODISHA', 'country_id' => 1, 'gst_code' => '21'],
            ['name' => 'PONDICHERRY', 'country_id' => 1, 'gst_code' => '34'],
            ['name' => 'PUNJAB', 'country_id' => 1, 'gst_code' => '03'],
            ['name' => 'RAJASTHAN', 'country_id' => 1, 'gst_code' => '08'],
            ['name' => 'SIKKIM', 'country_id' => 1, 'gst_code' => '11'],
            ['name' => 'TAMIL NADU', 'country_id' => 1, 'gst_code' => '33'],
            ['name' => 'TELANGANA', 'country_id' => 1, 'gst_code' => '36'],
            ['name' => 'TRIPURA', 'country_id' => 1, 'gst_code' => '16'],
            ['name' => 'UTTAR PRADESH', 'country_id' => 1, 'gst_code' => '09'],
            ['name' => 'UTTARAKHAND', 'country_id' => 1, 'gst_code' => '05'],
            ['name' => 'WEST BENGAL', 'country_id' => 1, 'gst_code' => '19'],
        ]);
    }
}
