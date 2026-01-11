<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveApplication extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'total_days',
        'is_half_day',
        'half_day_type',
        'substitute_user_id',
        'approval_status',
        'reason',
        'payroll_locked',
        'payroll_lock_till',
        'applied_at',
        'revoked_at',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'is_half_day' => 'boolean',
        'payroll_locked' => 'boolean',
    ];

    // 🔗 Polymorphic approval
    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    // 🔗 Date-wise instances
    public function instances()
    {
        return $this->hasMany(LeaveInstance::class);
    }

    // 🔗 Employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // 🔗 Leave Type
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    // 🔗 Substitute
    public function substitute()
    {
        return $this->belongsTo(User::class, 'substitute_user_id');
    }
}
