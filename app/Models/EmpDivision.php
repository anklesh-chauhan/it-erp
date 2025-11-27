<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class EmpDivision extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'description', 'department_id'];

    public function department()
    {
        return $this->belongsTo(EmpDepartment::class, 'department_id');
    }

    public function employmentDetails()
    {
        return $this->hasMany(EmploymentDetail::class, 'division_id');
    }
}
