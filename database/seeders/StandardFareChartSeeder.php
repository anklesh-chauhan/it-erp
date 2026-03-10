<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\TransportMode;
use App\Models\StandardFareChart;
use App\Models\Territory;

class StandardFareChartSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch all modes from your existing TransportModeSeeder
        $modes = TransportMode::all()->keyBy('code');

        // 2. Define the Routes (Source => [Target Hub => Distance])
        $hubs = [
            'Ahmedabad' => [
                'Mumbai' => 525, 'Surat' => 265, 'Vadodara' => 110, 'Rajkot' => 215, 'Delhi' => 950,
            ],
            'Mumbai' => [
                'Pune' => 150, 'Nashik' => 170, 'Bangalore' => 980, 'Hyderabad' => 715,
            ],
            'Bangalore' => [
                'Chennai' => 350, 'Mysore' => 145, 'Hyderabad' => 575, 'Kochi' => 530,
            ]
        ];

        foreach ($hubs as $toCityName => $origins) {
            $toCity = City::where('name', $toCityName)->first();
            if (!$toCity) continue;

            foreach ($origins as $fromCityName => $km) {
                $fromCity = City::where('name', $fromCityName)->first();
                if (!$fromCity) continue;

                // Respects your TerritorySeeder logic
                $territory = Territory::where('name', "{$fromCityName} Territory")->first();

                foreach ($modes as $code => $mode) {
                    $fare = 0;

                    // Only set fare_amount for public transport
                    if (in_array($code, ['TRAIN', 'BUS', 'AIR'])) {
                        $fare = $this->calculatePublicFare($code, $km);
                    }

                    StandardFareChart::updateOrCreate(
                        [
                            'from_city_id'      => $fromCity->id,
                            'to_city_id'        => $toCity->id,
                            'transport_mode_id' => $mode->id,
                        ],
                        [
                            'distance_km'  => $km,
                            'fare_amount'  => $fare,
                            'territory_id' => $territory?->id,
                            'is_active'    => true,
                        ]
                    );
                }
            }
            $this->command->info("Seeded all transport modes for routes to {$toCityName}.");
        }
    }

    /**
     * Estimates a standard ticket price for public transport
     */
    private function calculatePublicFare(string $code, int $km): float
    {
        return match ($code) {
            'BUS'   => 150 + ($km * 2.2),  // Base 150 + 2.2/km
            'TRAIN' => 100 + ($km * 1.5),  // Base 100 + 1.5/km (Sleeper/3A avg)
            'AIR'   => 2500 + ($km * 4.0), // Base 2500 + 4/km
            default => 0,
        };
    }
}
