<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class CompanyMasterStatutoryDetail extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'company_master_id',
        'credit_days',
        'credit_limit',
        'cin',
        'tds_parameters',
        'is_tds_deduct',
        'is_tds_compulsory',
    ];

    /**
     * Relationship with CompanyMaster
     */
    public function companyMaster()
    {
        return $this->belongsTo(CompanyMaster::class);
    }
}
