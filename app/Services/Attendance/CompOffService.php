<?php

namespace App\Services\Attendance;

use App\Models\ShiftMaster;

class CompOffService
{
    public function apply(
        ShiftMaster $shift,
        array $punchData,
        array $result
    ): array {
        $setup = $shift->compOffSetup;

        if (! $setup || ! $setup->is_active) {
            return $result;
        }

        $otMinutes = $result['ot_minutes'] ?? 0;

        // 1️⃣ Minimum OT minutes required
        if (
            $setup->minimum_comp_off_hours_required_per_day !== null &&
            $otMinutes < ($setup->minimum_comp_off_hours_required_per_day * 60)
        ) {
            return $result;
        }

        // 2️⃣ Only Weekly Off / Paid Holiday if enabled
        if (
            $setup->is_weekly_off_paid_holiday_as_comp_off &&
            empty($punchData['is_weekly_off']) &&
            empty($punchData['is_paid_holiday'])
        ) {
            return $result;
        }

        // 3️⃣ Convert OT → Comp Off
        $otHours = floor($otMinutes / 60);

        if (
            $setup->conversion_daily_ot_hours !== null &&
            $otHours < $setup->conversion_daily_ot_hours
        ) {
            return $result;
        }

        // 4️⃣ Grant comp-off
        $result['comp_off_days'] +=
            $setup->conversion_co_plus_credit_days ?? 0;

        return $result;
    }
}
