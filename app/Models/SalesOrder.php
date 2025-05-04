<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends SalesDocument
{
    protected $table = 'sales_orders';

    protected $fillable = [
        ...parent::FILLABLE,
        'account_master_id',
        'delivery_date',
        'order_confirmation_at',
    ];

    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    protected $casts = [
        'account_master_id' => 'integer',
        'delivery_date' => 'date',
        'order_confirmation_at' => 'datetime',
    ];

}
