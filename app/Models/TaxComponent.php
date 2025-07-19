<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_id',
        'component_type',
        'rate',
        'description',
    ];

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
