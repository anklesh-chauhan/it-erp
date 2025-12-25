<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PackingType;

class PackingTypeSeeder extends Seeder
{
    public function run()
    {
        PackingType::insert([
            ['name' => 'Box', 'description' => 'Packaged in a box'],
            ['name' => 'Carton', 'description' => 'Packaged in a carton'],
            ['name' => 'Bag', 'description' => 'Packaged in a bag'],
            ['name' => 'Bottle', 'description' => 'Packaged in a bottle'],
            ['name' => 'Drum', 'description' => 'Packaged in a drum'],
            ['name' => 'Pallet', 'description' => 'Packaged on a pallet'],
        ]);
    }
}
