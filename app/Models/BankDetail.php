<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Traits\HasApprovalWorkflow;

class BankDetail extends Model
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $table = 'bank_details';

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
        'created_by',
        'updated_by',
        'deleted_by',
        'is_deleted',
        'is_active',
        'is_default',
        'is_verified',
        'is_primary',
        'bankable_id',
        'bankable_type',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_verified' => 'boolean',
        'is_primary' => 'boolean',
    ];

    /**
     * Relationship: Belongs to Account Master.
     */
    public function bankable(): MorphTo
    {
        return $this->morphTo();
    }
}
