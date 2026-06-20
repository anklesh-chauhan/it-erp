<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryMovement extends BaseModel
{
    protected $fillable = [
        'item_master_id',
        'location_master_id',
        'reference_type',
        'reference_id',
        'movement_type',
        'quantity_in',
        'quantity_out',
        'balance_after',
        'unit_cost',
        'total_value',
        'movement_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'quantity_in' => 'decimal:3',
            'quantity_out' => 'decimal:3',
            'balance_after' => 'decimal:3',
            'unit_cost' => 'decimal:4',
            'total_value' => 'decimal:4',
            'movement_at' => 'datetime',
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

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
