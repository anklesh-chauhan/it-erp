<?php

namespace App\Models;

use Database\Factories\MarketingCampaignItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCampaignItem extends Model
{
    /** @use HasFactory<MarketingCampaignItemFactory> */
    use HasFactory;

    protected $fillable = [
        'marketing_campaign_id',
        'item_master_id',
        'total_quota',
        'unit_value',
    ];

    protected function casts(): array
    {
        return [
            'total_quota' => 'decimal:3',
            'unit_value' => 'decimal:2',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'marketing_campaign_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    public function usedQuantity(): float
    {
        return (float) $this->campaign
            ?->territoryQuotas()
            ->where('item_master_id', $this->item_master_id)
            ->sum('used_quantity');
    }

    public function remainingQuota(): float
    {
        return max(0, (float) $this->total_quota - $this->usedQuantity());
    }
}
