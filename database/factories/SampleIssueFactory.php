<?php

namespace Database\Factories;

use App\Enums\SampleIssueStatus;
use App\Models\LocationMaster;
use App\Models\SampleRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampleIssue>
 */
class SampleIssueFactory extends Factory
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
            'from_location_id' => LocationMaster::query()->inRandomOrder()->value('id'),
            'to_location_id' => LocationMaster::query()->inRandomOrder()->value('id'),
            'issue_date' => fake()->date(),
            'status' => SampleIssueStatus::Draft,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
