<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMasterStatutoryDetail extends Model
{
    use HasFactory;

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
