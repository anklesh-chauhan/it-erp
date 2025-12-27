<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class EmpActivity extends BaseModel
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'employee_id', 'activity_type', 'old_values', 'new_values', 'activity_date',
        'performed_by', 'remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'activity_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
