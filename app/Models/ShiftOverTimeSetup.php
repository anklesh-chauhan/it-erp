<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftOverTimeSetup extends BaseModel
{
    protected $table = 'shift_over_time_setups';

    protected $fillable = [
        'shift_master_id',
        'is_active',

        'is_weekly_off_paid_holiday_as_ot',

        'minimum_ot_hours_required_per_day',

        'ot_calculation_basis',
        'ot_rounding_method',

        'maximum_ot_hours_allowed_per_day',
        'maximum_ot_hours_per_month',

        'consider_working_hours_as_ot_if_less_than_half_day_hours',

        'monthly_total_ot_round_off_method',

        'is_approval_required',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_weekly_off_paid_holiday_as_ot' => 'boolean',
        'consider_working_hours_as_ot_if_less_than_half_day_hours' => 'boolean',
        'is_approval_required' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }

    /**
     * Enum constants (recommended)
     */
    public const CALCULATION_FIXED = 'fixed_hours';
    public const CALCULATION_ACTUAL = 'actual_hours';

    public const ROUND_NONE = 'none';
    public const ROUND_BASED_ON_SLAB = 'based_on_slab';
    public const ROUND_15 = 'up_to_nearest_15_minutes';
    public const ROUND_30 = 'up_to_nearest_30_minutes';
    public const ROUND_60 = 'up_to_nearest_hour';
}
