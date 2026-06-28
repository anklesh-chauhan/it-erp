<?php

namespace App\Models;

use Database\Factories\MarketingCampaignTerritoryQuotaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCampaignTerritoryQuota extends Model
{
    /** @use HasFactory<MarketingCampaignTerritoryQuotaFactory> */
    use HasFactory;

    protected $fillable = [
        'marketing_campaign_id',
        'territory_id',
        'item_master_id',
        'quota_quantity',
        'used_quantity',
    ];

    protected function casts(): array
    {
        return [
            'quota_quantity' => 'decimal:3',
            'used_quantity' => 'decimal:3',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'marketing_campaign_id');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }

    public function remainingQuota(): float
    {
        return max(0, (float) $this->quota_quantity - (float) $this->used_quantity);
    }
}
