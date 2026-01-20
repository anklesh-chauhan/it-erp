<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveLedgerEntry extends Model
{
    protected $table = 'leave_ledger'; // virtual
    protected $primaryKey = 'id';

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];
}
