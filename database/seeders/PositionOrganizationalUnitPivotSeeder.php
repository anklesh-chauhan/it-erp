<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionOrganizationalUnitPivotSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/position_organizational_unit_pivot.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }

            DB::table('position_organizational_unit_pivot')->insert($data);
        }
    }
}
