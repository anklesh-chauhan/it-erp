<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CityPinCode;
use Illuminate\Support\LazyCollection;

class CityPinCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('CityPinCodeSeeder.csv');

        $handle = fopen($file, 'r');
        $batch = [];
        $batchSize = 500;

        // 👉 Read header row once and ignore it
        $header = fgetcsv($handle);
        // ['pin_code', 'area_town', 'city_id', 'state_id', 'country_id']

        while (($row = fgetcsv($handle)) !== false) {
            $batch[] = [
                'pin_code'   => $row[0], // 786174
                'area_town' => $row[1], // Adarshgaon
                'city_id'   => $row[2], // 600
                'state_id'  => $row[3], // 4
                'country_id'=> $row[4], // 1
                'created_at'=> now(),
                'updated_at'=> now(),
            ];

            if (count($batch) === $batchSize) {
                CityPinCode::insert($batch);
                $batch = []; // 🔑 free memory
            }
        }

        // insert remaining rows
        if (! empty($batch)) {
            CityPinCode::insert($batch);
        }

        fclose($handle);

    }
}
