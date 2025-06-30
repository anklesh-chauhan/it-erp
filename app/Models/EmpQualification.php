<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpQualification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'degree', 'institution', 'year_of_completion', 'certification',
        'grade', 'percentage', 'remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'year_of_completion' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
