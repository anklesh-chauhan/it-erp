<?php

namespace Database\Seeders;

use App\Enums\TerritoryStatus;
use App\Models\City;
use App\Models\CityPinCode;
use App\Models\Territory;
use Illuminate\Database\Seeder;

class TerritorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Ahmedabad',
            'Mahesana',
            'Bhavnagar',
            'Vadodara',
        ];

        foreach ($cities as $cityName) {

            $city = City::where('name', $cityName)->first();

            if (! $city) {
                $this->command->warn("City not found: {$cityName}");

                continue;
            }

            // Create / fetch territory
            $territory = Territory::firstOrCreate(
                ['name' => "{$cityName} Territory"],
                [
                    'code' => strtoupper(substr($cityName, 0, 3)),
                    'status' => TerritoryStatus::Active->value,
                    'type_master_id' => null,
                    'description' => "Auto-created territory for {$cityName}",
                ]
            );

            // Fetch ALL pincodes for this city
            $pinCodeIds = CityPinCode::where('city_id', $city->id)
                ->pluck('id');

            if ($pinCodeIds->isEmpty()) {
                $this->command->warn("No pincodes found for {$cityName}");

                continue;
            }

            // Attach ALL pincodes
            $territory->cityPinCodes()->sync($pinCodeIds);

            $this->command->info(
                "{$cityName}: Territory created with {$pinCodeIds->count()} pincodes"
            );
        }
    }
}
