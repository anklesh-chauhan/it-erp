<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends SalesDocument
{
    protected $table = 'sales_invoices';

    protected $fillable = [
        ...parent::FILLABLE,
        'account_master_id',
        'due_date',
        'payment_status',
        'paid_at',
    ];


    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    public function getPaymentStatusAttribute($value)
    {
        return $value === 'paid' ? 'Paid' : ($value === 'unpaid' ? 'Unpaid' : 'Partially Paid');
    }
    public function setPaymentStatusAttribute($value)
    {
        $this->attributes['payment_status'] = strtolower($value);
    }
}
