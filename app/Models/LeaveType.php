<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_paid',
        'affects_payroll',
        'is_active',
        'employee_attendance_status_id',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'affects_payroll' => 'boolean',
    ];

    /* Relations */
    public function employeeAttendanceStatus()
    {
        return $this->belongsTo(EmployeeAttendanceStatus::class);
    }
}
