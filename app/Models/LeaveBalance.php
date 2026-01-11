<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveBalance extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'opening_balance',
        'year_start_date',
        'year_end_date',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'year_start_date' => 'date',
        'year_end_date'   => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
