<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesDocumentRegisterRow extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'sales_document_register_rows';

    protected $keyType = 'string';

    protected $guarded = [];

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function accountMaster(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class, 'account_master_id');
    }
}
