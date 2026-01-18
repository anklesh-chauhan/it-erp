<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Position;

class PositionEmployeeMapperSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /* ================= LOAD DATA ================= */
            $positions = Position::orderBy('level')->orderBy('id')->get();
            $employees = Employee::orderBy('id')->get();

            if ($positions->isEmpty()) {
                $this->command->error('No positions found. Run PositionSeeder first.');
                return;
            }

            if ($employees->isEmpty()) {
                $this->command->error('No employees found. Seed employees first.');
                return;
            }

            $employeeIndex = 0;
            $employeeCount = $employees->count();

            foreach ($positions as $position) {

                $required = $this->employeesRequired($position);

                for ($i = 0; $i < $required; $i++) {

                    if ($employeeIndex >= $employeeCount) {
                        $this->command->warn('Not enough employees to map all positions.');
                        return;
                    }

                    $employee = $employees[$employeeIndex++];

                    // 🔗 Attach sequentially
                    $position->employees()->syncWithoutDetaching([
                        $employee->id,
                    ]);
                }
            }

            $this->command->info('Employees sequentially mapped to positions successfully.');
        });
    }

    /**
     * Employees required per position
     */
    protected function employeesRequired(Position $position): int
    {
        if (str_contains($position->name, 'Sales Executive')) {
            return 2;
        }

        return 1;
    }
}
