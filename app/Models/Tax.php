<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'total_rate',
        'supply_type',
        'is_active',
        'is_default',
        'description',
    ];

    protected $casts = [
        'total_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Relationship: Components like CGST, SGST, IGST, CESS etc.
     */
    public function components()
    {
        return $this->hasMany(TaxComponent::class);
    }

    
    public function items()
    {
        return $this->belongsToMany(ItemMaster::class, 'item_master_tax_pivots')
            ->withTimestamps();
    }

    /**
     * Scope for active taxes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted total rate with percent symbol
     */
    public function getFormattedRateAttribute(): string
    {
        return number_format($this->total_rate, 2) . '%';
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->total_rate}%)";
    }
}
