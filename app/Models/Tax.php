<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class Tax extends BaseModel
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $fillable = ['name', 'total_rate', 'is_active'];

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
