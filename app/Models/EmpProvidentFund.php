<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class EmpProvidentFund extends Model
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'employee_id', 'pf_flag', 'pf_no', 'pf_new_version', 'pf_remarks', 'pf_join_date',
        'fpf_no', 'vpf_percentage', 'pf_account_type', 'pf_account_bank', 'pf_account_number',
        'pf_account_ifsc', 'pf_account_branch', 'pf_account_remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'pf_flag' => 'boolean',
        'vpf_percentage' => 'decimal:2',
        'pf_join_date' => 'date',
        'pf_account_type' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
