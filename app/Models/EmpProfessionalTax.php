<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpProfessionalTax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'pt_flag', 'pt_no', 'pt_amount', 'pt_join_date', 'pt_remarks',
        'pt_state', 'pt_city', 'pt_zone', 'pt_code', 'pt_jv_code', 'pt_jv_code_cr',
        'pt_jv_code_dr', 'pt_jv_code_remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'pt_flag' => 'boolean',
        'pt_amount' => 'decimal:2',
        'pt_join_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
