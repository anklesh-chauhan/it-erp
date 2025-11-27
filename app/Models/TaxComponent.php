<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class TaxComponent extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['tax_id', 'type', 'rate'];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    /**
     * Component types
     */
    public const COMPONENT_TYPES = [
        'CGST',
        'SGST',
        'IGST',
        'CESS',
        'VAT',
        'EXCISE',
        'CUSTOM',
    ];

    /**
     * Relationship: Belongs to Tax
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Accessor: Get formatted rate
     */
    public function getFormattedRateAttribute(): string
    {
        return number_format($this->rate, 2) . '%';
    }

    /**
     * Scope by component type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('component_type', $type);
    }
}
