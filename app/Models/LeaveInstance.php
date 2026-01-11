<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveInstance extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'leave_application_id',
        'employee_id',
        'leave_type_id',
        'date',
        'pay_factor',
        'approval_status',
        'is_half_day',
        'approved_at',
    ];

    protected $casts = [
        'date'        => 'date',
        'pay_factor'  => 'decimal:2',
        'is_half_day' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /* Scopes */
    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('approval_status', 'approved');
    }

    /* Relations */

    public function leaveApplication(): BelongsTo
    {
        return $this->belongsTo(LeaveApplication::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
