<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftDayWorkSetup extends BaseModel
{
    protected $table = 'shift_day_work_setups';

    protected $fillable = [
        'shift_master_id',
        'is_active',

        'first_half_late_in_cutoff_minutes',
        'first_half_early_out_cutoff_minutes',
        'second_half_late_in_cutoff_minutes',
        'second_half_early_out_cutoff_minutes',

        'add_early_in_minutes',
        'add_late_out_minutes',

        'daily_early_in_limit_minutes',
        'daily_late_out_limit_minutes',

        'monthly_early_in_grace_no_of_times',
        'monthly_late_out_grace_no_of_times',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'add_early_in_minutes' => 'boolean',
        'add_late_out_minutes' => 'boolean',
    ];

    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }
}
