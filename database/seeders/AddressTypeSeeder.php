<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('address_types')->insert([
            ['name' => 'Office'],
            ['name' => 'Shipping'],
            ['name' => 'Factory'],
            ['name' => 'Mailing (postal)'],
            ['name' => 'Physical'],
            ['name' => 'Residential'],
        ]);
    }
}
