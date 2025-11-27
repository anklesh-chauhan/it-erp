<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AccountMasterCreditType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class AccountMasterCreditDetail extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $table = 'account_master_credit_details';

    protected $fillable = [
        'account_master_id',
        'credit_type',
        'credit_days',
        'credit_limit',
        'credit_status',
        'credit_review_date',
        'credit_terms',
        'remark',
    ];

    protected $casts = [
        'credit_type' => AccountMasterCreditType::class,
        'credit_review_date' => 'date',
        'credit_limit' => 'decimal:2',
    ];

    /**
     * Get the related Account Master.
     */
    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }
}
