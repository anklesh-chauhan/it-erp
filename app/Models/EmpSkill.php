<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpSkill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'skill_name', 'proficiency_level', 'remarks', 'created_by', 'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
