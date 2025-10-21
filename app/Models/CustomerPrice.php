<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'item_master_id',
        'price',
        'discount',
    ];

    /**
     * Relationships
     */

    public function customer()
    {
        return $this->belongsTo(AccountMaster::class, 'customer_id');
    }

    public function item()
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    /**
     * Accessors
     */

    public function getItemNameAttribute(): string
    {
        return $this->item?->item_name ?? '-';
    }

    public function getIsVariantAttribute(): bool
    {
        return !is_null($this->item?->parent_id);
    }
}
