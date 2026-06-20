<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStock extends BaseModel
{
    protected $fillable = [
        'item_master_id',
        'location_master_id',
        'quantity_on_hand',
        'quantity_reserved',
        'quantity_available',
        'average_cost',
        'last_movement_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_on_hand' => 'decimal:3',
            'quantity_reserved' => 'decimal:3',
            'quantity_available' => 'decimal:3',
            'average_cost' => 'decimal:4',
            'last_movement_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationMaster::class, 'location_master_id');
    }
}
