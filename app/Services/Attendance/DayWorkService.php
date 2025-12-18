<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;

class DayWorkService
{
    public function apply(
        ShiftMaster $shift,
        array $punchData,
        array $result
    ): array {
        $setup = $shift->dayWorkSetup;

        if (! $setup || ! $setup->is_active) {
            return $result;
        }

        // First half late-in cutoff
        if (
            $punchData['late_in_minutes'] >
            ($setup->first_half_late_in_cutoff_minutes ?? PHP_INT_MAX)
        ) {
            $result['is_half_day'] = true;
        }

        // Second half early-out cutoff
        if (
            $punchData['early_out_minutes'] >
            ($setup->second_half_early_out_cutoff_minutes ?? PHP_INT_MAX)
        ) {
            $result['is_half_day'] = true;
        }

        return $result;
    }
}
