<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends SalesDocument
{
    protected $table = 'quotes';

    protected $fillable = [
        ...parent::FILLABLE,
        'account_master_id',
        'expiration_date',
        'accepted_at',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'accepted_at' => 'datetime',
        'expiration_date' => 'date',
    ];

    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }

}
