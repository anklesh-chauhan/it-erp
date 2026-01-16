<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePositionPivotSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/employee_position_pivot.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }

            DB::table('employee_position_pivot')->insert($data);
        }
    }
}
