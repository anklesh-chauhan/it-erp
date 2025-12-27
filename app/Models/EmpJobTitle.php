<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class EmpJobTitle extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['title', 'description', 'department_id'];

    public function department()
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function employmentDetails()
    {
        return $this->hasMany(EmploymentDetail::class, 'job_title_id');
    }
}
