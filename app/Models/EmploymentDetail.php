<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'ticket_no', 'department_id', 'job_title_id', 'grade_id',
        'division_id', 'organizational_unit_id', 'hire_date', 'employment_type',
        'employment_status', 'resign_offer_date', 'last_working_date', 'probation_date',
        'confirm_date', 'fnf_retiring_date', 'last_increment_date', 'work_location_id',
        'reporting_manager_id', 'remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'resign_offer_date' => 'date',
        'last_working_date' => 'date',
        'probation_date' => 'date',
        'confirm_date' => 'date',
        'fnf_retiring_date' => 'date',
        'last_increment_date' => 'date',
        'employment_type' => 'string',
        'employment_status' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function jobTitle()
    {
        return $this->belongsTo(EmpJobTitle::class, 'job_title_id');
    }

    public function grade()
    {
        return $this->belongsTo(EmpGrade::class, 'grade_id');
    }

    public function division()
    {
        return $this->belongsTo(EmpDivision::class, 'division_id');
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_unit_id');
    }

    public function workLocation()
    {
        return $this->belongsTo(LocationMaster::class, 'work_location_id');
    }

    public function reportingManager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager_id');
    }
}
