<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMasterBankDetail extends Model
{
    protected $fillable = [
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_account_ifsc_code',
        'bank_account_swift_code',
        'bank_account_code',
        'bank_account_type',
        'bank_account_currency',
        'bank_account_status',
        'bank_account_branch',
        'bank_account_iban',
        'bank_account_bic',
        'bank_account_phone',
        'bank_account_email',
        'bank_account_address',
        'bank_account_city',
        'bank_account_state',
        'bank_account_country',
        'bank_account_zip',
        'bank_account_tax_id',
        'bank_account_micr_code',
        'bank_account_rtgs_code',
        'bank_account_ecs_code',
        'remark',
        'account_master_id',
    ];

    /**
     * Relationship: Belongs to Account Master.
     */
    public function accountMaster(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class);
    }
}
