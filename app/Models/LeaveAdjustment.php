<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAdjustment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'type',
        'days',
        'reason',
        'effective_date',
        'created_by',
    ];

    protected $casts = [
        'days'           => 'decimal:2',
        'effective_date' => 'date',
    ];

    /* Scopes */
    public function scopePositive(Builder $q): Builder
    {
        return $q->where('type', 'positive');
    }

    public function scopeNegative(Builder $q): Builder
    {
        return $q->where('type', 'negative');
    }

    public function scopeForEmployee(
        Builder $q,
        int $employeeId,
        int $leaveTypeId
    ): Builder {
        return $q->where('employee_id', $employeeId)
                 ->where('leave_type_id', $leaveTypeId);
    }

    public function scopeAfter(Builder $q, $date): Builder
    {
        return $q->whereDate('effective_date', '>', $date);
    }

    /* Relations */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
