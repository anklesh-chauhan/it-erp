<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftLateMarkSetup extends Model
{
    protected $table = 'shift_late_mark_setups';

    protected $fillable = [
        'shift_master_id',
        'is_active',

        'late_in_grace_minutes',
        'early_out_grace_minutes',

        'total_late_in_early_out_mark_threshold_minutes_in_month',
        'total_late_in_early_out_mark_no_of_times_in_month',

        'is_save_late_minutes_as_late_mark',
        'is_calculate_on_weekly_off_and_paid_holiday',

        'is_mark_abs_once_late_mark_grace_crossed_in_a_month',

        'is_avoid_latemark_on_half_day_absent',
        'is_avoid_latemark_on_full_day_absent',

        'conversion_rate_grace_late_mark_count',
        'conversion_rate_no_of_late_mark_count',
        'conversion_rate_no_of_day_absent',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_save_late_minutes_as_late_mark' => 'boolean',
        'is_calculate_on_weekly_off_and_paid_holiday' => 'boolean',
        'is_mark_abs_once_late_mark_grace_crossed_in_a_month' => 'boolean',
        'is_avoid_latemark_on_half_day_absent' => 'boolean',
        'is_avoid_latemark_on_full_day_absent' => 'boolean',
    ];

    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }
}
