<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackagingType;

class PackagingTypeSeeder extends Seeder
{
    public function run()
    {
        $packagingTypes = [
            ['name' => 'Box', 'description' => 'Standard cardboard box'],
            ['name' => 'Carton', 'description' => 'Carton packaging'],
            ['name' => 'Bag', 'description' => 'Plastic or paper bag'],
            ['name' => 'Bottle', 'description' => 'Plastic or glass bottle'],
            ['name' => 'Pallet', 'description' => 'Wooden or plastic pallet'],
            ['name' => 'Drum', 'description' => 'Metal or plastic drum'],
        ];

        foreach ($packagingTypes as $type) {
            PackagingType::create($type);
        }
    }
}
