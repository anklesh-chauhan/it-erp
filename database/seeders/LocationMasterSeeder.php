<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationMaster;

class LocationMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocationMaster::factory()->count(20)->create();
        $ho = LocationMaster::factory()->create();
        LocationMaster::factory()->count(5)->subLocation($ho)->create();
    }
}
