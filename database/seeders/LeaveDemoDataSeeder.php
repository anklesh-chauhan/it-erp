<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Employee,
    LeaveType,
    LeaveBalance,
    LeaveApplication,
    LeaveInstance,
    LeaveAdjustment,
    LeaveEncashment,
    LeaveLapseRecord,
    PayrollLeaveSnapshot
};

class LeaveDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('is_active', true)->get();

        if ($employees->isEmpty()) {
            $this->command->error('No employees found. Run SalesUsersSeeder first.');
            return;
        }

        $leaveTypes = collect([
            LeaveType::factory()->casual()->create(),
            LeaveType::factory()->sick()->create(),
            LeaveType::factory()->privilege()->create(),
            LeaveType::factory()->lwp()->create(),
            LeaveType::factory()->compOff()->create(),
        ]);

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {

                // 🔹 Opening balance
                LeaveBalance::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Create Leave Application (PARENT)
                $application = LeaveApplication::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Create Leave Instances (CHILD)
                LeaveInstance::factory()->count(3)->create([
                    'leave_application_id' => $application->id,
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Adjustments
                LeaveAdjustment::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Encashment
                LeaveEncashment::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Lapse
                LeaveLapseRecord::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);

                // 🔹 Payroll snapshot
                PayrollLeaveSnapshot::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                ]);
            }
        }

        $this->command->info('Leave demo data seeded successfully.');
    }
}
