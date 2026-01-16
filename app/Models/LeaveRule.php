<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LogicException;

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

    protected static function booted(): void
    {
        static::saving(function (LeaveRule $rule) {

            // Only validate active rules
            if (! $rule->is_active) {
                return;
            }

            // Notification rules MUST define an event
            if (
                $rule->category?->key === 'notification'
                && empty($rule->action_json['event'])
            ) {
                throw new LogicException(
                    'Notification rule must define an event'
                );
            }
        });
    }
}
