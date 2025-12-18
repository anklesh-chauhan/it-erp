<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;
use App\Models\ShiftOverTimeSetup;

class OverTimeService
{
    public function apply(
        ShiftMaster $shift,
        array $punchData,
        array $result
    ): array {
        $setup = $shift->overTimeSetup;

        if (! $setup || ! $setup->is_active) {
            return $result;
        }

        // Base working minutes (after breaks)
        $workedMinutes = $result['final_working_minutes'] ?? 0;

        // Shift expected minutes
        $shiftMinutes = $shift->shift_duration_hours
            ? $shift->shift_duration_hours * 60
            : 0;

        if ($workedMinutes <= $shiftMinutes) {
            $result['ot_minutes'] = 0;
            return $result;
        }

        // Raw OT
        $otMinutes = $workedMinutes - $shiftMinutes;

        // Minimum OT per day
        if (
            $setup->minimum_ot_hours_required_per_day !== null &&
            $otMinutes < ($setup->minimum_ot_hours_required_per_day * 60)
        ) {
            $result['ot_minutes'] = 0;
            return $result;
        }

        // 🔁 OT Rounding
        switch ($setup->ot_rounding_method) {

            case ShiftOverTimeSetup::ROUND_BASED_ON_SLAB:
                $roundedHours = app(TimeSlabService::class)
                    ->match($shift->id, 'round_off_ot_hours', $otMinutes);

                $otMinutes = ($roundedHours ?? 0) * 60;
                break;

            case ShiftOverTimeSetup::ROUND_15:
                $otMinutes = $this->roundUpMinutes($otMinutes, 15);
                break;

            case ShiftOverTimeSetup::ROUND_30:
                $otMinutes = $this->roundUpMinutes($otMinutes, 30);
                break;

            case ShiftOverTimeSetup::ROUND_60:
                $otMinutes = $this->roundUpMinutes($otMinutes, 60);
                break;
        }


        // Maximum OT per day
        if ($setup->maximum_ot_hours_allowed_per_day !== null) {
            $otMinutes = min(
                $otMinutes,
                $setup->maximum_ot_hours_allowed_per_day * 60
            );
        }

        $result['ot_minutes'] = max(0, $otMinutes);

        return $result;
    }

    private function roundUpMinutes(int $minutes, int $nearest): int
    {
        return (int) ceil($minutes / $nearest) * $nearest;
    }
}
