<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemBrand;

class ItemBrandSeeder extends Seeder
{
    public function run()
    {
        ItemBrand::insert([
            ['name' => 'Brand A'],
            ['name' => 'Brand B'],
            ['name' => 'Brand C'],
        ]);
    }
}

