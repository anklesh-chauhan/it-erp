<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class SalesDocumentItem extends Model
{
    use HasApprovalWorkflow;

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
        'final_taxable_amount',
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
