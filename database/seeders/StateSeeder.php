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
        $states = [
            ['id' => 1, 'name' => 'Andaman And nicobar islands', 'country_id' => 1, 'gst_code' => '35'],
            ['id' => 2, 'name' => 'Andhra Pradesh', 'country_id' => 1, 'gst_code' => '37'],
            ['id' => 3, 'name' => 'Arunachal Pradesh', 'country_id' => 1, 'gst_code' => '12'],
            ['id' => 4, 'name' => 'Assam', 'country_id' => 1, 'gst_code' => '18'],
            ['id' => 5, 'name' => 'Bihar', 'country_id' => 1, 'gst_code' => '10'],
            ['id' => 6, 'name' => 'Chandigarh', 'country_id' => 1, 'gst_code' => '4'],
            ['id' => 7, 'name' => 'Chattisgarh', 'country_id' => 1, 'gst_code' => '22'],
            ['id' => 8, 'name' => 'Dadra And nagar haveli', 'country_id' => 1, 'gst_code' => '26'],
            ['id' => 9, 'name' => 'Daman And diu', 'country_id' => 1, 'gst_code' => '25'],
            ['id' => 10, 'name' => 'Delhi', 'country_id' => 1, 'gst_code' => '7'],
            ['id' => 11, 'name' => 'Goa', 'country_id' => 1, 'gst_code' => '30'],
            ['id' => 12, 'name' => 'Gujarat', 'country_id' => 1, 'gst_code' => '24'],
            ['id' => 13, 'name' => 'Haryana', 'country_id' => 1, 'gst_code' => '6'],
            ['id' => 14, 'name' => 'Himachal Pradesh', 'country_id' => 1, 'gst_code' => '2'],
            ['id' => 15, 'name' => 'Jammu And kashmir', 'country_id' => 1, 'gst_code' => '1'],
            ['id' => 16, 'name' => 'Jharkhand', 'country_id' => 1, 'gst_code' => '20'],
            ['id' => 17, 'name' => 'Karnataka', 'country_id' => 1, 'gst_code' => '29'],
            ['id' => 18, 'name' => 'Kerala', 'country_id' => 1, 'gst_code' => '32'],
            ['id' => 19, 'name' => 'Lakshadweep', 'country_id' => 1, 'gst_code' => '31'],
            ['id' => 20, 'name' => 'Madhya Pradesh', 'country_id' => 1, 'gst_code' => '23'],
            ['id' => 21, 'name' => 'Maharashtra', 'country_id' => 1, 'gst_code' => '27'],
            ['id' => 22, 'name' => 'Manipur', 'country_id' => 1, 'gst_code' => '14'],
            ['id' => 23, 'name' => 'Meghalaya', 'country_id' => 1, 'gst_code' => '17'],
            ['id' => 24, 'name' => 'Mizoram', 'country_id' => 1, 'gst_code' => '15'],
            ['id' => 25, 'name' => 'Nagaland', 'country_id' => 1, 'gst_code' => '13'],
            ['id' => 26, 'name' => 'Null', 'country_id' => 1, 'gst_code' => '0'],
            ['id' => 27, 'name' => 'Orissa', 'country_id' => 1, 'gst_code' => '21'],
            ['id' => 28, 'name' => 'Pondicherry', 'country_id' => 1, 'gst_code' => '34'],
            ['id' => 29, 'name' => 'Punjab', 'country_id' => 1, 'gst_code' => '3'],
            ['id' => 30, 'name' => 'Rajasthan', 'country_id' => 1, 'gst_code' => '8'],
            ['id' => 31, 'name' => 'Sikkim', 'country_id' => 1, 'gst_code' => '11'],
            ['id' => 32, 'name' => 'State', 'country_id' => 1, 'gst_code' => '33'],
            ['id' => 33, 'name' => 'Tamil Nadu', 'country_id' => 1, 'gst_code' => '36'],
            ['id' => 34, 'name' => 'Tripura', 'country_id' => 1, 'gst_code' => '16'],
            ['id' => 35, 'name' => 'Uttar Pradesh', 'country_id' => 1, 'gst_code' => '9'],
            ['id' => 36, 'name' => 'Uttarakhand', 'country_id' => 1, 'gst_code' => '5'],
            ['id' => 37, 'name' => 'West Bengal', 'country_id' => 1, 'gst_code' => '19'],
        ];

        DB::table('states')->insert($states);
    }
}
