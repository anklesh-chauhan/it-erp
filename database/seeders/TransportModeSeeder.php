<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransportMode;

class TransportModeSeeder extends Seeder
{
    public function run()
    {
        TransportMode::insert([
            ['name' => 'Air'],
            ['name' => 'Sea'],
            ['name' => 'Road'],
            ['name' => 'Rail'],
        ]);
    }
}
