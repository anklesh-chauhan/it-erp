<?php

namespace App\Services\Attendance;

use App\Models\ShiftTimeSlabSetup;

class TimeSlabService
{
    /**
     * Match minutes against configured time slabs
     *
     * @return int|float
     */
    public function match(
        int $shiftId,
        string $type,
        int $minutes,
        int|float $default = 0
    ): int|float {
        if ($minutes <= 0) {
            return $default;
        }

        $slab = ShiftTimeSlabSetup::query()
            ->where('shift_master_id', $shiftId)
            ->where('time_slab_type', $type)
            ->where('from_minute', '<=', $minutes)
            ->where('to_minute', '>=', $minutes)
            ->orderBy('from_minute')
            ->first();

        return $slab?->diff_calc ?? $default;
    }
}
