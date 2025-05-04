<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMasterBankDetail extends Model
{
    use HasFactory;

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
