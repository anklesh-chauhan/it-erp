<?php

namespace Database\Factories;

use App\Models\ItemMaster;
use App\Models\MarketingCampaign;
use App\Models\MarketingCampaignTerritoryQuota;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MarketingCampaignTerritoryQuota>
 */
class MarketingCampaignTerritoryQuotaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quota = fake()->randomFloat(3, 10, 100);

        return [
            'marketing_campaign_id' => MarketingCampaign::factory(),
            'territory_id' => Territory::query()->inRandomOrder()->value('id'),
            'item_master_id' => ItemMaster::query()->inRandomOrder()->value('id') ?? ItemMaster::factory(),
            'quota_quantity' => $quota,
            'used_quantity' => fake()->randomFloat(3, 0, $quota),
        ];
    }
}
