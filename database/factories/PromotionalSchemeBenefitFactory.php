<?php

namespace Database\Factories;

use App\Enums\PromotionalBenefitType;
use App\Models\ItemMaster;
use App\Models\PromotionalScheme;
use App\Models\PromotionalSchemeBenefit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromotionalSchemeBenefit>
 */
class PromotionalSchemeBenefitFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'promotional_scheme_id' => PromotionalScheme::factory(),
            'benefit_type' => fake()->randomElement(PromotionalBenefitType::cases()),
            'item_master_id' => ItemMaster::query()->inRandomOrder()->value('id') ?? ItemMaster::factory(),
            'buy_quantity' => fake()->randomFloat(3, 1, 10),
            'get_quantity' => fake()->randomFloat(3, 1, 5),
            'discount_value' => fake()->randomFloat(4, 1, 25),
            'min_quantity' => fake()->randomFloat(3, 1, 10),
            'max_quantity' => fake()->randomFloat(3, 10, 50),
            'remarks' => fake()->sentence(),
        ];
    }
}
