<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class GstPan extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'company_id',
        'company_master_id',
        'address_id',
        'pan_number',
        'gst_number',
    ];

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with CompanyMaster
     */
    public function companyMaster()
    {
        return $this->belongsTo(CompanyMaster::class);
    }

    /**
     * Relationship with Address
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
