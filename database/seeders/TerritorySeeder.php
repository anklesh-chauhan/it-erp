<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Territory;
use App\Helpers\TypeMasterHelper;
use App\Models\CityPinCode;
use App\Enums\TerritoryStatus;

class TerritorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Ahmedabad',
            'Mumbai',
            'Bangalore',
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
