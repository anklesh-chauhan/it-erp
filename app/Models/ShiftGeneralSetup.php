<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftGeneralSetup extends BaseModel
{
    protected $table = 'shift_general_setups';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'shift_master_id',

        'reduce_lunch_break_minutes_from_working_hours',
        'reduce_dinner_break_minutes_from_working_hours',
        'reduce_break_minutes_from_working_hours',

        'auto_convert_wop_to_co_plus',
        'auto_convert_php_to_co_plus',

        'calculate_compensation',
        'is_allow_auto_shift',
        'is_allow_half_day_leave',
        'is_allow_shift_change_request',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'reduce_lunch_break_minutes_from_working_hours' => 'boolean',
        'reduce_dinner_break_minutes_from_working_hours' => 'boolean',
        'reduce_break_minutes_from_working_hours' => 'boolean',

        'auto_convert_wop_to_co_plus' => 'boolean',
        'auto_convert_php_to_co_plus' => 'boolean',

        'calculate_compensation' => 'boolean',
        'is_allow_auto_shift' => 'boolean',
        'is_allow_half_day_leave' => 'boolean',
        'is_allow_shift_change_request' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }
}
