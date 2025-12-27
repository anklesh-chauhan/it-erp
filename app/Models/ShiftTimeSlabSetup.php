<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class ShiftTimeSlabSetup extends BaseModel
{
    protected $table = 'shift_time_slab_setups';

    protected $fillable = [
        'shift_master_id',
        'time_slab_type',
        'from_minute',
        'to_minute',
        'diff_calc',
    ];

    protected $casts = [
        'from_minute' => 'integer',
        'to_minute' => 'integer',
        'diff_calc' => 'integer',
    ];

    /**
     * Relationships
     */
    public function shiftMaster(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class);
    }

    /**
     * Slab types (constants)
     */
    public const TYPE_LATE_IN = 'late_in';
    public const TYPE_LATE_OUT = 'late_out';
    public const TYPE_COMPENSATION_HOURS = 'compensation_hours';
    public const TYPE_ROUND_OFF_OT_HOURS = 'round_off_ot_hours';

    protected static function booted()
    {
        static::saving(function ($slab) {

            if ($slab->from_minute >= $slab->to_minute) {
                throw ValidationException::withMessages([
                    'from_minute' => 'From minute must be less than To minute.',
                ]);
            }

            $exists = self::query()
                ->where('shift_master_id', $slab->shift_master_id)
                ->where('time_slab_type', $slab->time_slab_type)
                ->where('id', '!=', $slab->id)
                ->where(function ($q) use ($slab) {
                    $q->whereBetween('from_minute', [$slab->from_minute, $slab->to_minute])
                    ->orWhereBetween('to_minute', [$slab->from_minute, $slab->to_minute])
                    ->orWhere(function ($q) use ($slab) {
                        $q->where('from_minute', '<=', $slab->from_minute)
                            ->where('to_minute', '>=', $slab->to_minute);
                    });
                })
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'from_minute' => 'Time slab overlaps with an existing slab.',
                ]);
            }
        });
    }
}
