<?php

namespace Database\Factories;

use App\Enums\MarketingCampaignStatus;
use App\Models\MarketingCampaign;
use App\Models\PromotionalScheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MarketingCampaign>
 */
class MarketingCampaignFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 week', '+1 month');

        return [
            'campaign_number' => strtoupper(fake()->unique()->bothify('CAMP-####')),
            'name' => fake()->words(3, true),
            'promotional_scheme_id' => PromotionalScheme::factory(),
            'status' => MarketingCampaignStatus::Draft,
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '+3 months'),
            'total_budget' => fake()->randomFloat(2, 10000, 100000),
            'description' => fake()->sentence(),
        ];
    }
}
