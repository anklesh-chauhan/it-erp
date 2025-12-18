<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;

class GeneralSetupService
{
    public function apply(
        ShiftMaster $shift,
        array $punchData,
        array $result
    ): array {
        $setup = $shift->generalSetup;

        if (! $setup) {
            return $result;
        }

        // Start from actual working minutes
        $finalMinutes = $punchData['actual_working_minutes'] ?? 0;

        if ($setup->reduce_lunch_break_minutes_from_working_hours) {
            $finalMinutes -= $shift->lunch_break_minutes ?? 0;
        }

        if ($setup->reduce_dinner_break_minutes_from_working_hours) {
            $finalMinutes -= $shift->dinner_break_minutes ?? 0;
        }

        if ($setup->reduce_break_minutes_from_working_hours) {
            $finalMinutes -= $shift->break_minutes ?? 0;
        }

        // Never allow negative working minutes
        $result['final_working_minutes'] = max(0, $finalMinutes);

        return $result;
    }
}
