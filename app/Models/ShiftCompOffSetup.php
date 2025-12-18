<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftCompOffSetup extends Model
{
    protected $table = 'shift_comp_off_setups';

    protected $fillable = [
        'shift_master_id',
        'is_active',

        'is_weekly_off_paid_holiday_as_comp_off',

        'minimum_comp_off_hours_required_per_day',

        'conversion_daily_ot_hours',
        'conversion_co_plus_credit_days',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_weekly_off_paid_holiday_as_comp_off' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }
}
