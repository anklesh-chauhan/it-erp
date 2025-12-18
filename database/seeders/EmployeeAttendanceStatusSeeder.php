<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeAttendanceStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employee_attendance_statuses')->insert([
            [
                'status_code' => 'DP',
                'status'      => 'Day Present',
                'remarks'     => 'Employee present for full day.',
                'color_code'  => '#009900',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'status_code' => 'SP',
                'status'      => 'Single Punch',
                'remarks'     => 'Only check-in or check-out recorded. Requires manual review.',
                'color_code'  => '#FFB703',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'status_code' => 'HD',
                'status'      => 'Half Day',
                'remarks'     => 'Employee present for half day.',
                'color_code'  => '#FFB703',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'status_code' => 'ABS',
                'status'      => 'Absent',
                'remarks'     => 'Employee was absent for the day.',
                'color_code'  => '#FFD3D0',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'AL',
                'status'      => 'Annual Leave',
                'remarks'     => 'Annual leave approved.',
                'color_code'  => '#009933',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'CL',
                'status'      => 'Casual Leave',
                'remarks'     => 'Casual leave taken.',
                'color_code'  => '#FFFF66',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'CO+',
                'status'      => 'Compensatory Leave Accrued',
                'remarks'     => 'Comp-off earned.',
                'color_code'  => '#AECBFF',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'CO-',
                'status'      => 'Compensatory Leave Enjoyed',
                'remarks'     => 'Comp-off utilized.',
                'color_code'  => '#C0B09A',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],


            [
                'status_code' => 'EO',
                'status'      => 'Extra Ordinary Leave',
                'remarks'     => 'Extra ordinary leave approved.',
                'color_code'  => '#FF758A',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'status_code' => 'LWP',
                'status'      => 'Leave Without Pay',
                'remarks'     => 'Unpaid leave.',
                'color_code'  => '#999966',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'ML',
                'status'      => 'Maternity Leave',
                'remarks'     => 'Maternity leave approved.',
                'color_code'  => '#66CCFF',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'OD',
                'status'      => 'Outdoor Duty',
                'remarks'     => 'Working outside office.',
                'color_code'  => '#BBFFE6',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'OTHL',
                'status'      => 'Other Leave',
                'remarks'     => 'Other leave type.',
                'color_code'  => '#3399FF',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'PH',
                'status'      => 'Paid Holiday',
                'remarks'     => 'Paid company holiday.',
                'color_code'  => '#C5A094',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'PHP',
                'status'      => 'Paid Holiday Present',
                'remarks'     => 'Present on paid holiday.',
                'color_code'  => '#CBCFEF',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'PL',
                'status'      => 'Privilege Leave',
                'remarks'     => 'Privilege leave taken.',
                'color_code'  => '#5AB15A',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'PNL',
                'status'      => 'Paternity Leave',
                'remarks'     => 'Paternity leave approved.',
                'color_code'  => '#33FFCC',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'SL',
                'status'      => 'Sick Leave',
                'remarks'     => 'Sick leave approved.',
                'color_code'  => '#D9B0FF',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'WO',
                'status'      => 'Weekly Off',
                'remarks'     => 'Weekly off day.',
                'color_code'  => '#FFCC00',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'status_code' => 'WOP',
                'status'      => 'Weekly Off Present',
                'remarks'     => 'Employee worked on weekly off.',
                'color_code'  => '#E8B979',
                'is_system'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
