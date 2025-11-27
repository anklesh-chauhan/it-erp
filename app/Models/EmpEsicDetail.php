<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class EmpEsicDetail extends Model
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'employee_id', 'esic_flag', 'esic_old_no', 'esic_new_version', 'esic_imp_code',
        'esic_imp_name', 'esic_remarks', 'esic_account_bank', 'esic_account_number',
        'esic_account_ifsc', 'esic_account_branch', 'esic_account_remarks',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'esic_flag' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
