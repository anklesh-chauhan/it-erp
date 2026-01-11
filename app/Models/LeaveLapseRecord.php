<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveLapseRecord extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'days',
        'lapsed_on',
        'reason',
    ];

    protected $casts = [
        'days'      => 'decimal:2',
        'lapsed_on' => 'date',
    ];

    /* Scopes */
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
        return $q->whereDate('lapsed_on', '>', $date);
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
