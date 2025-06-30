<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpGrade extends Model
{
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
