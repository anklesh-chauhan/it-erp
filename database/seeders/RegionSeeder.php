<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            'North India',
            'South India',
            'West India',
            'East India',
            'Central India',
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate([
                'name' => $region,
            ]);
        }
    }
}
