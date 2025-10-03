<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMasterGSTDetail extends Model
{
    use HasFactory;
    protected $table = 'account_master_g_s_t_details';

    protected $fillable = [
        'account_master_id',
        'address_id',
        'gst_number',
        'state_name',
        'state_code',
        'gst_type',
        'gst_status',
        'pan_number',
        'remark',
    ];

    /**
     * Relationship: GST detail belongs to Account Master.
     */
    public function accountMaster(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class);
    }

    /**
     * Relationship: GST detail belongs to Address.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
