<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMasterStatutoryDetail extends Model
{
    use HasFactory;
    
    protected $table = 'account_master_statutory_details';

    protected $fillable = [
        'account_master_id',
        'tan_number',
        'cin',
        'tds_parameters',
        'tds_section',
        'tds_rate',
        'tds_type',
        'tds_status',
        'is_tds_deduct',
        'is_tds_compulsory',
        'tds_remark',
    ];

    /**
     * Relationship: Statutory detail belongs to Account Master.
     */
    public function accountMaster(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class);
    }
}
