<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpJobTitle extends Model
{
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
