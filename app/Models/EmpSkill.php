<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class EmpSkill extends BaseModel
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'employee_id', 'skill_name', 'proficiency_level', 'remarks', 'created_by', 'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
