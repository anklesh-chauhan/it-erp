<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class TaxDetail extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'taxable_type',
        'taxable_id',
        'tax_id',
        'tax_component_id',
        'type',
        'rate',
        'amount',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Polymorphic relation to taxable models (Quote, SalesOrder, SalesInvoice, etc.)
     */
    public function taxable()
    {
        return $this->morphTo();
    }

    /**
     * Related Tax (e.g., GST 18%)
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Related Tax Component (e.g., CGST 9%)
     */
    public function component()
    {
        return $this->belongsTo(TaxComponent::class, 'tax_component_id');
    }

    /**
     * Get formatted tax label (e.g., CGST 9% → ₹123.45)
     */
    public function getLabelAttribute(): string
    {
        return "{$this->type} {$this->rate}% → ₹" . number_format($this->amount, 2);
    }
}
