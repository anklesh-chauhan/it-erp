<?php

namespace Database\Factories;

use App\Enums\PromotionalSchemeAppliesTo;
use App\Enums\PromotionalSchemeStatus;
use App\Enums\PromotionalSchemeType;
use App\Models\PromotionalScheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromotionalScheme>
 */
class PromotionalSchemeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $validFrom = fake()->dateTimeBetween('-1 month', '+1 week');

        return [
            'code' => strtoupper(fake()->unique()->bothify('PROMO-####')),
            'name' => fake()->words(3, true),
            'scheme_type' => fake()->randomElement(PromotionalSchemeType::cases()),
            'status' => PromotionalSchemeStatus::Draft,
            'applies_to' => PromotionalSchemeAppliesTo::Global,
            'applies_to_id' => null,
            'valid_from' => $validFrom,
            'valid_to' => fake()->dateTimeBetween($validFrom, '+3 months'),
            'min_order_value' => fake()->randomFloat(2, 0, 5000),
            'description' => fake()->sentence(),
        ];
    }
}
