<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class EmpGrade extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['grade_name', 'description', 'department_id'];

    public function department()
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function employmentDetails()
    {
        return $this->hasMany(EmploymentDetail::class, 'grade_id');
    }
}
