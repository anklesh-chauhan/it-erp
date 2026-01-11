<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRule extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'rule_key',
        'leave_rule_category_id',
        'leave_type_id',
        'name',
        'description',
        'condition_json',
        'action_json',
        'priority',
        'employee_attendance_status_id',
        'is_active',
    ];

    protected $casts = [
        'condition_json' => 'array',
        'action_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(LeaveRuleCategory::class, 'leave_rule_category_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function attendanceStatus()
    {
        return $this->belongsTo(EmployeeAttendanceStatus::class, 'employee_attendance_status_id');
    }
}
