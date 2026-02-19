<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransportMode;

class TransportModeSeeder extends Seeder
{
    public function run()
    {
        $modes = [
            [
                'name' => 'Own Car',
                'code' => 'CAR',
                'is_distance_based' => true,
            ],
            [
                'name' => 'Motorcycle',
                'code' => 'BIKE',
                'is_distance_based' => true,
            ],
            [
                'name' => 'Bus',
                'code' => 'BUS',
                'is_distance_based' => false,
            ],
            [
                'name' => 'Train',
                'code' => 'TRAIN',
                'is_distance_based' => false,
            ],
            [
                'name' => 'Flight',
                'code' => 'AIR',
                'is_distance_based' => false,
            ],
        ];

        foreach ($modes as $mode) {
            TransportMode::updateOrCreate(['code' => $mode['code']], $mode);
        }
    }
}
