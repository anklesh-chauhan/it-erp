<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class CompanyMasterBankDetail extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'company_master_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'name_in_bank',
        'remarks',
    ];

    /**
     * Relationship with CompanyMaster
     */
    public function companyMaster()
    {
        return $this->belongsTo(CompanyMaster::class);
    }
}
