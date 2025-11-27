<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class EmpStatutoryId extends Model
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'employee_id', 'pan', 'uan_no', 'group_join_date', 'gratuity_code', 'pran',
        'aadhar_number', 'tax_code', 'tax_exemption', 'tax_exemption_reason',
        'tax_exemption_validity', 'tax_exemption_remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'group_join_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
