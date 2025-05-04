<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDocumentItem extends Model
{
    protected $table = 'sales_document_items';

    protected $fillable = [
        'document_id',
        'document_type',
        'item_master_id',
        'quantity',
        'price',
        'discount',
        'unit',
        'unit_price',
        'hsn_sac', // Harmonized System Nomenclature/SAC (Service Accounting Code)
        'tax_rate',
        'amount',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function document()
    {
        return $this->morphTo();
    }

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class);
    }
}
