<?php

namespace Database\Factories;

use App\Models\ItemMaster;
use App\Models\SampleRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampleRequestLine>
 */
class SampleRequestLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sample_request_id' => SampleRequest::factory(),
            'item_master_id' => ItemMaster::query()->whereNotNull('sgip_type')->inRandomOrder()->value('id'),
            'quantity_requested' => fake()->numberBetween(1, 20),
            'quantity_approved' => 0,
            'quantity_issued' => 0,
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
