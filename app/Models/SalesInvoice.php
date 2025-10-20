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
        'other_ref'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    public function salesOrders()
    {
        return $this->belongsToMany(SalesOrder::class, 'sales_order_sales_invoice_pivot');
    }

    public function quotes()
    {
        return $this->belongsToMany(Quote::class, 'quote_sales_invoice_pivot');
    }

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
