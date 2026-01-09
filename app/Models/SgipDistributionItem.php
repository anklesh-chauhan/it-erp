<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SgipDistributionItem extends BaseModel
{
    use HasFactory;

    protected $table = 'sgip_distribution_item_pivot';

    protected $fillable = [
        'sgip_distribution_id',
        'item_master_id',
        'quantity',
        'unit_value',
        'total_value',
    ];

    protected $casts = [
        'unit_value'  => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    /* ============================
     | Relationships
     ============================ */

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(SgipDistribution::class, 'sgip_distribution_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    /* ============================
     | Model Events
     ============================ */

    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->total_value = $item->quantity * $item->unit_value;
        });

        static::saved(function ($item) {
            $item->distribution?->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->distribution?->recalculateTotal();
        });
    }
}
