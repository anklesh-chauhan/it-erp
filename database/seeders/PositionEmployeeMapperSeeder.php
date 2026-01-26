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
            $positions = Position::orderBy('level')->orderBy('id')->get();
            $employees = Employee::orderBy('id')->get();

            if ($positions->isEmpty() || $employees->isEmpty()) {
                $this->command->error('Missing positions or employees.');
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

                    // 🔗 Attach with pivot data
                    // We set is_primary to true because this seeder assigns
                    // the main position for each employee.
                    $position->employees()->syncWithoutDetaching([
                        $employee->id => [
                            'is_primary' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);
                }
            }

            $this->command->info('Employees mapped with primary positions successfully.');
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
