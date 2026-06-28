<?php

namespace Database\Factories;

use App\Models\ItemMaster;
use App\Models\MarketingCampaign;
use App\Models\MarketingCampaignItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MarketingCampaignItem>
 */
class MarketingCampaignItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'marketing_campaign_id' => MarketingCampaign::factory(),
            'item_master_id' => ItemMaster::query()->inRandomOrder()->value('id') ?? ItemMaster::factory(),
            'total_quota' => fake()->randomFloat(3, 50, 500),
            'unit_value' => fake()->randomFloat(2, 10, 500),
        ];
    }
}
