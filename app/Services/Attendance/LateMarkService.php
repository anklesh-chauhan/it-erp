<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;

class LateMarkService
{
    public function apply(
        ShiftMaster $shift,
        array $punchData,
        array $result
    ): array {
        $setup = $shift->lateMarkSetup;

        if (! $setup || ! $setup->is_active) {
            return $result;
        }

        // Use normalized late-in minutes
        $lateMinutes = $punchData['late_in_minutes'] ?? 0;

        // Grace minutes check
        if ($lateMinutes <= ($setup->late_in_grace_minutes ?? 0)) {
            return $result;
        }

        // Convert late minutes → late marks using slab
        $lateMarkCount = app(TimeSlabService::class)
            ->match($shift->id, 'late_in', $lateMinutes);

        $result['late_mark_count'] += (int) $lateMarkCount;

        /**
         * DAILY-level absent rule
         * (Monthly aggregation should be handled elsewhere)
         */
        if (
            $setup->is_mark_abs_once_late_mark_grace_crossed_in_a_month &&
            $result['late_mark_count'] >=
                ($setup->conversion_rate_no_of_late_mark_count ?? PHP_INT_MAX)
        ) {
            $result['is_absent'] = true;
        }

        return $result;
    }
}
