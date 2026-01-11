<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollLeaveSnapshot extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'processed_till',
        'opening_balance',
        'closing_balance',
        'payroll_run_id',
    ];

    protected $casts = [
        'processed_till'  => 'date',
        'opening_balance'=> 'decimal:2',
        'closing_balance'=> 'decimal:2',
    ];

    /* Scopes */
    public function scopeLatestForEmployee(
        Builder $q,
        int $employeeId,
        int $leaveTypeId
    ): ?self {
        return $q->where('employee_id', $employeeId)
                 ->where('leave_type_id', $leaveTypeId)
                 ->orderByDesc('processed_till')
                 ->first();
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
