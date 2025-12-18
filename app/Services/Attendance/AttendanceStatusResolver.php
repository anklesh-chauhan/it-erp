<?php

namespace App\Services\Attendance;

use App\Models\EmployeeAttendanceStatus;

class AttendanceStatusResolver
{
    public function resolve(array $data): EmployeeAttendanceStatus
    {
        $workingMinutes = $data['final_working_minutes'] ?? 0;
        // dd($workingMinutes);
        $code = match (true) {

            /* ================= LEAVES (PRE-APPROVED) ================= */
            ! empty($data['leave_code']) => $data['leave_code'],

            /* ================= PAID HOLIDAY ================= */
            ($data['is_paid_holiday'] ?? false) && $workingMinutes === 0 => 'PH',

            /* ================= PAID HOLIDAY PRESENT ================= */
            ($data['is_paid_holiday'] ?? false) && $workingMinutes > 0 => 'PHP',

            /* ================= WEEKLY OFF ================= */
            ($data['is_weekly_off'] ?? false) && $workingMinutes === 0 => 'WO',

            /* ================= WEEKLY OFF PRESENT ================= */
            ($data['is_weekly_off'] ?? false) && $workingMinutes > 0 => 'WOP',

            /* ================= ABSENT ================= */
            ($data['is_absent'] ?? false) => 'ABS',

            /* ================= HALF DAY ================= */
            ($data['is_half_day'] ?? false) => 'HD',

            /* ================= SINGLE PUNCH ================= */
            ($data['single_punch'] ?? false) => 'SP',

            /* ================= DAY PRESENT ================= */
            default => 'DP',
        };
        // dd($code);
        return EmployeeAttendanceStatus::where('status_code', $code)->firstOrFail();
    }
}
