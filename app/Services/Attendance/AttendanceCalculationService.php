<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;

class AttendanceCalculationService
{
    public function calculate(
        ShiftMaster $shift,
        array $punchData,
        array $context = []
    ): array {
        /**
         * Canonical result contract
         */
        $result = [
            // Working minutes
            'final_working_minutes' => 0,

            // Raw deviations (from normalization)
            'late_in_minutes'  => $punchData['late_in_minutes']  ?? 0,
            'early_out_minutes'=> $punchData['early_out_minutes']?? 0,
            'early_in_minutes' => $punchData['early_in_minutes'] ?? 0,
            'late_out_minutes' => $punchData['late_out_minutes'] ?? 0,

            // Policy results
            'late_mark_count' => 0,
            'ot_minutes'      => 0,
            'comp_off_days'   => 0,

            // Day flags
            'is_half_day' => false,
            'is_absent'   => false,

            // Context passthrough (important)
            'is_weekly_off'   => $context['is_weekly_off']   ?? false,
            'is_paid_holiday' => $context['is_paid_holiday'] ?? false,
        ];

        /**
         * 1️⃣ General Setup
         * - produces final_working_minutes
         */
        $result = app(GeneralSetupService::class)
            ->apply($shift, $punchData, $result);

        /**
         * 2️⃣ Day Work Rules
         * - half day logic
         */
        $result = app(DayWorkService::class)
            ->apply($shift, $punchData, $result);

        /**
         * 3️⃣ Late Mark Rules
         * - late_mark_count
         * - absent (if policy says so)
         */
        $result = app(LateMarkService::class)
            ->apply($shift, $punchData, $result);

        /**
         * 4️⃣ Over Time Policy
         * - ot_minutes
         */
        $result = app(OverTimeService::class)
            ->apply($shift, $punchData, $result);

        /**
         * 5️⃣ Comp Off Rules
         * - comp_off_days
         */
        $result = app(CompOffService::class)
            ->apply($shift, $punchData, $result);

        /**
         * 6️⃣ Safety guards
         */
        if ($result['is_absent']) {
            $result['is_half_day'] = false;
            $result['ot_minutes'] = 0;
            $result['comp_off_days'] = 0;
        }

        return $result;
    }
}
