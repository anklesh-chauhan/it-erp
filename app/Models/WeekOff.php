<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeekOff extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'emp_department_id',
        'shift_master_id',
        'day_of_week',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     |-----------------------------------------------------------------*/

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'emp_department_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(ShiftMaster::class, 'shift_master_id');
    }

    /* -----------------------------------------------------------------
     | Scopes
     |-----------------------------------------------------------------*/

    /**
     * Only active week-offs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by day of week
     * 0 (Sunday) → 6 (Saturday)
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Resolve week-off hierarchy:
     * Employee → Department → Shift → Global
     */
    public function scopeApplicableFor($query, array $context)
    {
        return $query
            ->where(function ($q) use ($context) {
                $q->whereNull('employee_id')
                  ->orWhere('employee_id', $context['employee_id'] ?? null);
            })
            ->where(function ($q) use ($context) {
                $q->whereNull('emp_department_id')
                  ->orWhere('emp_department_id', $context['emp_department_id'] ?? null);
            })
            ->where(function ($q) use ($context) {
                $q->whereNull('shift_master_id')
                  ->orWhere('shift_master_id', $context['shift_master_id'] ?? null);
            });
    }

    /* -----------------------------------------------------------------
     | Helpers
     |-----------------------------------------------------------------*/

    public function getDayNameAttribute(): string
    {
        return match ($this->day_of_week) {
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            default => 'Unknown',
        };
    }

}
